# Covid Services USA NYTimes

Exposure of services in laravel with reading of data from the New York Times about the covid in USA. 

Backend developed with Laravel 8, taking data from:

https://raw.githubusercontent.com/nytimes/covid-19-data/master/us-states.csv

The data is stored in the laravel cache and then filters are applied with php's own functions.

## Docker

** Run Local **

### Docker Build: The following command is run once to generate the APP_ENVIRONMENT image (local, dev, prod)

```python
docker build . -t covid-back  --build-arg APP_ENVIRONMENT=local -f Dockerfile
```


### RUN: To run LOCAL
```python
docker run --rm --name=covid-back --env APP_ENVIRONMENT=local -v $PWD:/var/www/html/symfony -p 8000:80/tcp covid-back
```


## Author

Victor M Suarez (victor@virtualteamtech.com ; victormst@gmail.com)

## Services 1: GET => /api/covidLoadData/ 

Receive: Receive information from New York Time about Covid in the USA, save it in the Laravel Cache and expose it as data to view it.

Example: 


```python
{
    "message": "Succeed",
    "data": [
        {
            "id": 2,
            "date": "2020-01-21",
            "datestamp": 1579564800,
            "state": "Washington",
            "cases": "1",
            "deaths": "0"
        },
        .
        .
        .
    ]
    "code": 200
}
```


## Services 2: GET => /api/covidStates/

Receive: States of USA

Example: 


```python
{
    "message": "Succeed",
    "data": [
        "Washington",
        "Illinois",
        "California",
        "Arizona",
        "Massachusetts",
        "Wisconsin",
        .
        .
        .
    ]
    "code": 200
}
```



## Services 3: POST => /api/covid/

Data send: 

```python
{
    "state" : "California",
    "date"  : "2021-01-01"
}
```

State & date can be empty, it depends on the information as you request it.


Receive: Receive summary information from:

- All time total cases and deaths, per each US state
- All time total cases and deaths, from a provided US state
- Total cases and deaths, per each US state, from a provided date
- Total cases and deaths, from a provided date and US state

Example:

```python
{
    "message": "Succeed",
    "data": {
        "California": {
            "cases": 1278936139,
            "deaths": 19366783
        }
    },
    "code": 200
}
```

## Unit tests

The unit tests file is at:

tests / Feature / CovidTest.php

The results are:

```python

   PASS  Tests\Feature\CovidTest
  ✓ covid


  Tests:  1 passed
  Time:   2.65s
```

To perform the tests, you must place in the project console, the following command:


```python
php artisan test
```


## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.
