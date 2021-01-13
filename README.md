# la-casa-task
task implemntation: used laravel and jwt

to open project please do these steps

copy .env.example to .env and set database name pass

composer update

php artisan key:generate

php artisan jwt:secret

php artisan migrate --seed

now you have 2 default users in database


note there's file postman (end-points-task.postman_collection.json)  for all requests please import it in your postman and try all requests

routes:
localhost/la-casa-task/public/api/auth/register post request to check form in task 1

localhost/la-casa-task/public/api/auth/login to generate token after seed you can login by (amr@gmail.com, 123456789)
localhost/la-casa-task/public/api/states post request has phone_number and status and token in header request if phone for the own token will return user data else return invalid data

