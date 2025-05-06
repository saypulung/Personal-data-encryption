### Personal Data Encryption Implementation

#### Small Brief of this Project

It is about a simple implementation of personal data encryption in the database.

#### High Level

To implement personal data encryption, we need a cache to save every decrypted rows of the user data. To give a proper and stable, we need to made own server with good memory management like C/C++, Go, Rust or etc. Without cache data, the encrypted data not possible to handle like-search or sorting. With this example, I code with these tech-stacks: MariaDB, SQLite (in memory), PHP (Main API) and NodeJS (Cache-server).
The power of this implementation is on the NodeJS + SQLite (in memory).

This example will working as expectation by these steps:
1. Import the SQL file to the MariaDB server
2. Configure .env for the database section only
3. Go to `cache-server` and run it using NodeJs 20 with command `node server.js`
4. Go to `app` directory and run it using PHP 8.3 with command `composer start`


#### Step Implementation

1. Create a function to encrypt and decrypt using two programming languages (i.e PHP & NodeJS). Make sure if we are encrypt in PHP should be able decrypted in NodeJS using same key.
2. Create API endpoint in PHP to create user
3. Prepare API endpoint in NodeJS to load encrypted data and decrypt it then save into SQLite
4. Prepare API endpoint in NodeJS CRUD for in-memory database
5. Once user created, it should be create a cache to the SQLite memory database
6. Every user updated/delete, it should update/delete memory database too by userId
7. When user access search API, we call to NodeJS API to receive all of user IDs, then we can perform IN Query.