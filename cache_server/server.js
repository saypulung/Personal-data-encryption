require('dotenv').config({ path: '../.env'});
const encryption = require('./encryption');
const { Sequelize, DataTypes, Op } = require('sequelize');
const express = require('express');
const app = express();
app.use(express.urlencoded({ extended: false }));
const app_port = 3000;

const personCache = new Sequelize({ dialect: 'sqlite', storage: ':memory:'});

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

app.get('/', async (req, res) => {
    const people = await Person.findAll();

    const peopleInCache = (await DictionaryCache.findAll())
        .map(item => {
            return {col: item.col_name, word: item.word};
        });
    res.send('Wakwau');
});

app.post('/save-cache', async (req, res) => {
    const { secret } = req.params;
    if (!secret || secret !== secret_key ) {
        res.send('NO');
        return;
    }
    const { id, nik, name, cecar } = req.body;
    word.push({
        user_id: id,
        word: encryption.decrypt(nik, password),
        col_name: 'nik'
    });
    word.push({
        user_id: id,
        word: encryption.decrypt(name, password),
        col_name: 'name'
    });
    word.push({
        user_id: id,
        word: encryption.decrypt(cecar, password),
        col_name: 'cecar'
    });
    const pCache = await DictionaryCache.bulkCreate(word);
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
    res.send('OK');
});

app.put('/update-cache', async (req, res) => {
    const { secret } = req.params;
    if (!secret || secret !== secret_key ) {
        res.send('NO');
        return;
    }

    const { people } = req.body;
    console.log(people);
    res.send('Wakwau cache');
});

app.put('/delete-cache', async (req, res) => {
    const { secret } = req.params;
    if (!secret || secret !== secret_key ) {
        res.send('NO');
        return;
    }
    
    const { id } = req.body;
    if (!id) {
        res.send('NO');
        return;
    }

    await DictionaryCache.destroy({
        where: {
            user_id: id,
        }
    });
    res.send('OK');
});

app.get('/like-search', async (req, res) => {
    const { secret } = req.query;
    if (!secret || secret !== secret_key ) {
        res.json({ data : [] });
        return;
    }

    const { name } = req.body;
    const pDicts = await DictionaryCache.findAll(
        {
            attributes: ['user_id'],
            group: 'user_id',
            where: {
                col_name: 'name',
                word: {
                    [Op.like]: `%${name}%`
                }
            }
        }
    );
    res.json({data : pDicts.map(i => i.user_id )});
});

app.listen(app_port, () => {
    console.log('Listen to ' + app_port);
});