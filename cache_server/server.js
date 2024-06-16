require('dotenv').config({ path: '../.env'});
const encryption = require('./encryption');
const { Sequelize, DataTypes } = require('sequelize');
const express = require('express');
const app = express();
const app_port = 3000;

const personCache = new Sequelize('sqlite::memory:');

const db_host = process.env.DB_HOST;
const db_pass = process.env.DB_PASS;
const db_port = process.env.DB_PORT;
const db_user = process.env.DB_USER;
const db_name = process.env.DB_NAME;

const memory_table = process.env.IN_MEMORYDB;
const personDb = new Sequelize(`mariadb://${db_user}:${db_pass}@${db_host}:${db_port}/${db_name}`);

const DictionaryCache = personCache.define(
    'DictionaryCache',
    {
        id: { 
            type: DataTypes.INTEGER,
            autoIncrement: true,
            primaryKey: true,
        },
        user_id: { type: DataTypes.INTEGER },
        word: { type: DataTypes.STRING },
        col_name: { type: DataTypes.STRING }
    }
);

DictionaryCache.sync({ force: true });

const Person = personDb.define(
    'Person',
    {
        id : {
            type: DataTypes.INTEGER,
            autoIncrement: true,
            primaryKey: true,
        },
        nik: {
            type: DataTypes.STRING
        },
        name: {
            type: DataTypes.STRING
        },
        cecar: {
            type: DataTypes.STRING
        },
        idx_nik: {
            type: DataTypes.INTEGER
        },
        idx_name: {
            type: DataTypes.INTEGER
        },
        idx_cc: {
            type: DataTypes.INTEGER
        },
        created_at: {
            type: DataTypes.DATE
        },
        updated_at: {
            type: DataTypes.DATE
        },
    },
    {
        tableName: 'people',
        timestamps: false,
    }
);

const password = process.env.ENCRYPTION_KEY;
const secret_key = process.env.CREDENTIALS_TO_JS;

async function queryDb (offset, limit) {
    const people = await Person.findAll({ limit, offset });
    console.log(people);
    for (var i = 0; i < people.length; i++) {
        const p = people[i];
        const word = [];
        word.push({
            user_id: p.id,
            word: encryption.decrypt(p.nik, password),
            col_name: 'nik'
        });
        word.push({
            user_id: p.id,
            word: encryption.decrypt(p.name, password),
            col_name: 'name'
        });
        word.push({
            user_id: p.id,
            word: encryption.decrypt(p.cecar, password),
            col_name: 'cecar'
        });
        const pCache = await DictionaryCache.bulkCreate(word);
        console.log('save to cache');
        console.log(pCache);
        await Person.update(
            {
                idx_nik: pCache[0].id,
                idx_name: pCache[1].id,
                idx_cc: pCache[2].id
            },
            {
                where: { id: p.id }
            }
        );
    }
}

async function parseEncryptedToMemory()
{
    console.log('parse to cache');
    const count = await Person.count();
    console.log('count', count);
    const pages = Math.ceil(count / 100);
    for (var i = 0; i < pages; i++) {
        var currentOffset = i * 100;
        await queryDb(currentOffset, 100);
    }
}

parseEncryptedToMemory();

// const encrypted = encryption.encrypt('namaku bento', password);
// console.log(encrypted);
// const decrypted = encryption.decrypt(encrypted, password);
// console.log(decrypted);

app.get('/', async (req, res) => {
    const people = await Person.findAll();
    console.log(people);

    const peopleInCache = await DictionaryCache.findAll();
    console.log(peopleInCache);
    res.send('Wakwau');
});

app.post('/save-cache', async (req, res) => {
    
});

app.put('/update-cache', async (req, res) => {
    const people = Person.findAll();
    console.log(people);
    res.send('Wakwau');
});

app.put('/delete-cache', async (req, res) => {
    const people = Person.findAll();
    console.log(people);
    res.send('Wakwau');
});

app.listen(app_port, () => {
    console.log('Listen to ' + app_port);
});