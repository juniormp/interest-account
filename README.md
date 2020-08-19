## Overview Architecture and Design



The project follows the standard of **Clean Architecture** and **Domain Oriented Design**.

The main benefits of using these two concepts that I present in the developed project are the decoupling between layers, separation of responsibilities, as well as better organization and ease of maintenance.

These principles of how to define the architecture for the project and the layout of the class design help to approach the **SOLID** principles, both at the macro level of components (layers of the project) and micro components (classes).

[Clean Architecture and DDD](https://herbertograca.com/2017/11/16/explicit-architecture-01-ddd-hexagonal-onion-clean-cqrs-how-i-put-it-all-together/)

The project has 3 layers: Application, Domain and Infrastructure.

**Domain**

The core of the application. This layer is responsible for what has the greatest value to the project: All business knowledge and its abstraction from the real world for the software.

A layer where the use of [Ubiquitous Language ](https://martinfowler.com/bliki/UbiquitousLanguage.html) is essential to separate Business Domains and Contexts. In this way, the construction and representation of the software is the same as the processes that occur in real life.

This layer is protected, that is, it does not have references from other layers to guarantee its integrity and decoupling, so the details of an application (database, frameworks) can be changed over time and the Domain Layer will never be affected .

Some of the Design Patterns implemented in this layer are:

* Domain Model, for representing an entity in the real world.

* Factory, for building Domain Models.

* Policy, representation of rules.

* Domain Services, services that can be shared between Domains.

**Infrastructure**


An application support layer, all the details for the Use Cases (Behaviors) to happen are in that layer.

As an example: persistence of data, external communication with other services, tasks.

This layer has as one of its objectives not to hinder the maintenance of the application by updating its components.

E.g. Changing the persistence model should not affect the way the Domain Layer works (The Domain Layer should be independent of the others).

Some of the Design Patterns implemented in this layer are:

* Repository, interface with persistence actions.

* Providers, represents the actions for the persistence of an Entity

* Adapters, translators of information from the outside world to the application's Ubiquitous Language.

* Scheduler, task routine that starts a Use Case.

* Infrastructure Service, services for performing tasks.

**Application**

The front door of the project, the entire orchestration of Use Cases is found in this layer.

For a given Use Case (Behavior), components of the Domain and Infrastructure Layer are used to execute what must be done.

As it is a layer close to the client, it is responsible for carrying out the necessary initial validation of the information that will be used, as a measure of organization and security.

After executing the task, this layer is also responsible for how the response will be represented to the client.

Some of the Design Patterns implemented in this layer are:

* Application Service (Use Cases), are the business behaviors that a system has.

* Command, are DTOs (Data Transfer Objects) that group the information of a request to an Application Service.

* Validation, have information validation rules.

This is an overview of the project and the way it was built, so that easy and lasting maintenance is possible, as well as the abstraction of the Business Domain in the form of software so that the work on it is of the highest possible quality.


## Use Cases (Behaviors) and Tests


The possible behaviors that a client can initiate when using our application are:

* Open Account: Opens an interest account with the user's id.

* Deposit Funds: The client can deposit funds to an account with the user id.

* List Transactions: The client can check the list of transactions with the user id.

* Calculate Payout: Given the user id it is possible to calculate Payout.

* Close Payouts: Performs the calculation of the payout of all users of the application.

The tests for these behaviors are in **tests/Feature**.

Each test class is responsible for testing the possible success or exception flows for each Use Case.

For each possible scenario there is a description at the beginning of the test in the form of BDD.

E.g:

  /**

     * Since the client service wants to list all account transactions

     * And informed the user id (UUIDv4)

     * When entering this information through the interface

     * Then a list of transactions should be returned

     */



**Close Payouts** happens every three days. For that to happen in the right way, a cron job was created that is configured in Docker in the cronjob file.

The cronjob every minute runs lib crunz schedule: run, which knows the exact time to run PayoutTask.


## Unit Tests


For each class in the software, a test class that verifies its unitary behavior.

Unit tests are in **/tests**. And they follow the same structure as **/src**.

The classes are tested through PHPUnit and Mockery (this only for spy mocks).

Repositories and Factories are used for testing, in order to facilitate testing for the scenarios of each class.

In this way, support classes (Repository and Factory) were created, so these components are the same as those used in the Domain Layer but are abstracted for the tests so that if at any time they change the tests will reflect this change.

This abstraction is important so that the tests are tested in the most real way possible.

The Repositories and Factories are in **/tests/Support**.


## Integration Test


When opening a Use Case Open Account, the application consults an external service (Stats API) to obtain the user income that has a policy of parameterizing its interest rate.

The API is still under construction and for this reason its response and request contract was mocked through the Swagger Hub service.

<https://app.swaggerhub.com/apis-docs/juniormp/StatsAPI/1.0.0#/>

In this way the application works as if it were consulting the real service and getting the answer.

Responses were mocked for each type of scenario so that the interest rate matches the income that the API provides.

These scenarios are in **OpenAccountFeatureTest**.

## Project Setup

### Pre-requisites

* [Docker][]
* [Docker Compose][]

[Docker]: https://docs.docker.com/install/ "About Docker CE"
[Docker Compose]: https://docs.docker.com/compose/install/#install-compose "Install Docker Compose"

You must have the docker-compose version equal or superior to 3.7.

[Updating docker compose][]

[Updating docker compose]: https://linuxize.com/post/how-to-install-and-use-docker-compose-on-ubuntu-18-04/

###  Using Docker

Build the project with the command:
```cmd
docker-compose build
```

Install dependencies:
```cmd
docker run --rm -v $(pwd):/app composer update
```

Run the project with the command:
```cmd
docker-compose up -d
```

### Executing tests and lint

Run the tests
```cmd
docker-compose exec chip vendor/bin/phpunit
```

or


```cmd
composer test
```


Run lint

```cmd
composer lint
```

Run tests and lint

```cmd
composer complete
```


## Application Playground


In the **console.php** file it is possible to run the Use Cases to have the experience of how to use the system.

To Run **console.php**


```cmd
docker-compose exec chip bash
```

And

```cmd
php console.php
```


