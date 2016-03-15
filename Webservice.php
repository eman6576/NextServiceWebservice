<?php

include "ServiceProviderManager.php";

/**
 * Webservice that clients make requests and responses from.
 *
 * Created by PhpStorm.
 * User: Emanuel
 * Date: 3/14/16
 * Time: 8:26 PM
 */

if (!empty($_POST)) {
    $command = $_POST['command'];
    $response = null;

    //Check the command sent
    switch($command) {
        case "create":
            $response = createUser();
        case "read":
            $response = readUsers();
        case "update":
            $response = updateUser();
        case "delete":
            $response = deleteUser();
        case "check":
            $response = checkUser();
        default:
            $response = array('success' => 0,
                              'message' => "No commands sent");
    }

    header('Content-Type: application/json; charset=utf8');
    echo json_encode($response);
} else {
    $response = array('success' => 0,
                      'message' => "No parameters sent");
    header('Content-Type: application/json; charset=utf8');
    echo json_encode($response);
}

/**
 * Creates a new user.
 *
 * @return array a response to let the client know if the user was created successfully.
 */
function createUser() {
    $userName = $_POST['username'];
    $passWord = $_POST['password'];
    $firstName = $_POST['firstname'];
    $lastName = $_POST['lastname'];
    $streetAddress = $_POST['streetaddress'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zipCode = $_POST['zipcode'];
    $baseService = $_POST['baseservice'];
    $subServices = $_POST['subservices'];
    $description = $_POST['description'];

    $serviceProviderManager = new ServiceProviderManager();
    $response = $serviceProviderManager->createServiceProvider($userName, $passWord, $firstName, $lastName,
                                                               $streetAddress, $city, $state, $zipCode, $baseService,
                                                               $subServices, $description);

    return $response;
}

/**
 * Finds service providers.
 *
 * @return array a response to let the client know if there are search results or not.
 */
function readUsers() {
    $zipCode = $_POST['zipcode'];
    $baseService = $_POST['baseservice'];
    $subServices = $_POST['subservices'];

    $serviceProviderManager = new ServiceProviderManager();
    $response = $serviceProviderManager->findServiceProviders($zipCode, $baseService, $subServices);

    return $response;
}

/**
 * Updates a current user.
 *
 * @return array a response to let the client know if the user was updated.
 */
function updateUser() {
    $userName = $_POST['username'];
    $passWord = $_POST['password'];
    $firstName = $_POST['firstname'];
    $lastName = $_POST['lastname'];
    $streetAddress = $_POST['streetaddress'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zipCode = $_POST['zipcode'];
    $baseService = $_POST['baseservice'];
    $subServices = $_POST['subservices'];
    $description = $_POST['description'];

    $serviceProviderManager = new ServiceProviderManager();
    $response = $serviceProviderManager->updateServiceProvider($userName, $passWord, $firstName, $lastName,
                                                               $streetAddress, $city, $state, $zipCode, $baseService,
                                                               $subServices, $description);

    return $response;
}

/**
 * Deletes a user.
 *
 * @return array a response to let the client know if the user was deleted.
 */
function deleteUser() {
    $userName = $_POST['username'];

    $serviceProviderManager = new ServiceProviderManager();
    $response = $serviceProviderManager->deleteServiceProvider($userName);

    return $response;
}

/**
 * Authenticates a user.
 *
 * @return array a reponse to let the client know if the user exists in the database system.
 */
function checkUser() {
    $userName = $_POST['username'];
    $passWord = $_POST['password'];

    $serviceProviderManager = new ServiceProviderManager();
    $response = $serviceProviderManager->authenticateServiceProvider($userName, $passWord);

    return $response;
}