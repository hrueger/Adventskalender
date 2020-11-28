# AGventskalender
> A simple Advent calendar in German.

## Development
### Frontend
- `cd adventskalender`
- `npm i`
- `ng serve -o`

### Backend (API)
- Create a MySQL Database (for example using the *MySQL Workbench* or *phpMyAdmin*) with a useful name (e.g. *adventskalender_2020*)
- `cd api`
- `npm i`
- `npm start`
- You will get `Error: ER_BAD_DB_ERROR: Unknown database 'db'`. To fix this, open `/app/config/adventskalender.json` and replace the value of `DB_NAME` with the name of the database you created. You might also have to change the login credentials.
- Stop the api using `Ctrl + C` and start it again with `npm start`
- Now the api is available at port `3000`!

## License
MIT