<?php

include "MongoDBDriver.php";
include "HashUtility.php";

/**
 * Manages service providers information and creates JSON arrays to send back to the client side.
 * Created by PhpStorm.
 * User: Emanuel
 * Date: 2/13/16
 * Time: 1:50 AM
 */
class ServiceProviderManager {
    /**
     * Creates a service provider document and inserts it into the collection.
     *
     * @param string $username the username of the service provider used for login.
     * @param string $password the password of the service provider used for login.
     * @param string $firstName the first name of the service provider.
     * @param string $lastName the last name of the service provider.
     * @param string $streetAddress the street address of the service provider's business.
     * @param string $city the city that the service provider's business is located in.
     * @param string $state the state that the service provider's business is located in.
     * @param int $zipCode the five digit zip code of the service provider's business.
     * @param string $baseService the base service that the service provider provides.
     * @param array $subServices specific services that the service provider provides within the base service.
     * @param string $description information about the service that the service provider provides.
     * @return array notifies the client side if the insertion was successful.
     */
    public function createServiceProvider($username, $password, $firstName, $lastName, $streetAddress, $city,
                                          $state, $zipCode, $baseService, $subServices = array(), $description) {
        global $resultsOfInsertion;

        $findQuery = array('username' => $username);

        $mongoDBDriver = new MongoDBDriver();

        $cursor = $mongoDBDriver->findDocument($findQuery);

        if ($cursor != null) {
            if ($cursor->count() == 0) {
                $hashUtility = new HashUtility();

                $hashedPassword = $hashUtility->hash($password);

                $document = array('username' => $username,
                                  'password' => $hashedPassword,
                                  'firstname' => $firstName,
                                  'lastname' => $lastName,
                                  'streetaddress' => $streetAddress,
                                  'city' => $city,
                                  'state' => $state,
                                  'zipcode' => $zipCode,
                                  'baseservice' => $baseService,
                                  'subservices' => $subServices,
                                  'description' => $description);

                if ($mongoDBDriver->insertDocument($document)) {
                    $resultsOfInsertion = array('success' => 1,
                                                'message' => "Insertion success");
                } else {
                    $resultsOfInsertion = array('success' => 0,
                                                'message' => "Insertion failed");
                }
            } else {
                $resultsOfInsertion = array('success' => 0,
                                            'message' => "Username already exists");
            }
        } else {
            $resultsOfInsertion = array('success' => 0,
                                        'message' => "Connection error");
        }

        return $resultsOfInsertion;
    }

    /**
     * Finds service provider documents for the search listing on the client side.
     *
     * @param int $zipCode the location of the client who made the request.
     * @param string $baseService the base service that the client side is looking for.
     * @param string $subServices specific services that the client is side looking for.
     * @return array notifies the client side if service providers were found and the list of service providers.
     */
    public function findServiceProviders($zipCode, $baseService, $subServices) {
        global $resultOfQuery;

        $findQuery = array('zipcode' => $zipCode,
                           'baseservice' => $baseService,
                           'subservices' => $subServices);

        $mongoDBDriver = new MongoDBDriver();

        $cursor = $mongoDBDriver->findDocument($findQuery);

        if ($cursor == null) {
            $resultOfQuery = array('success' => 0,
                                   'message' => "Connection error");
        } else if ($cursor->count() == 0) {
            $resultOfQuery = array('success' => 0,
                                   'message' => "No service providers found");
        } else {
            $resultOfQuery = array('success' => 1,
                                   'message' => "Service providers found",
                                   'serviceproviders' => $cursor);
        }

        return $resultOfQuery;
    }

    /**
     * Updates a service provider document and updates the collection.
     *
     * @param string $username the username of the service provider who is logged in and used to find the document to be
     *                         updated.
     * @param string $password the new password of the service provider used for login.
     * @param string $firstName the new first name of the service provider.
     * @param string $lastName the new last name of the service provider.
     * @param string $streetAddress the new street address of the service provider's business.
     * @param string $city the new city that the service provider's business is located in.
     * @param string $state the new state that the service provider's business is located in.
     * @param int $zipCode the new five digit zip code of the service provider's business.
     * @param string $baseService the new base service that the service provider provides.
     * @param array $subServices the new specific services that the service provider provides within the base service.
     * @param string $description the new information about the service that the service provider provides.
     * @return array notifies the client side if the update was successful.
     */
    public function updateServiceProvider($username, $password, $firstName, $lastName, $streetAddress, $city,
                                          $state, $zipCode, $baseService, $subServices = array(), $description) {
        global $resultOfUpdate;

        $hashUtility = new HashUtility();

        $hashedPassword = $hashUtility->hash($password);

        $updateDocument = array('username' => $username,
                                'password' => $hashedPassword,
                                'firstname' => $firstName,
                                'lastname' => $lastName,
                                'streetaddress' => $streetAddress,
                                'city' => $city,
                                'state' => $state,
                                'zipcode' => $zipCode,
                                'baseservice' => $baseService,
                                'subservices' => $subServices,
                                'description' => $description);

        $findQuery = array('username' => $username);

        $mongoDBDriver = new MongoDBDriver();

        if ($mongoDBDriver->updateDocument($findQuery, $updateDocument)) {
            $resultOfUpdate = array('success' => 1,
                                    'message' => "Update success");
        } else {
            $resultOfUpdate = array('success' => 0,
                                    'message' => "Update failed");
        }

        return $resultOfUpdate;
    }

    /**
     * Deletes a service provider document from the collection.
     *
     * @param string $username the username of the service provider used to locate the document in the collection.
     * @return array notifies the client side if the deletion was successful.
     */
    public function deleteServiceProvider($username) {
        global $resultOfDeletion;

        $findQuery = array('username' => $username);

        $mongoDBDriver = new MongoDBDriver();

        if ($mongoDBDriver->deleteDocument($findQuery)) {
            $resultOfDeletion = array('success' => 1,
                                      'message' => "Deletion success");
        } else {
            $resultOfDeletion = array('success' => 0,
                                      'message' => "Deletion failed");
        }

        return $resultOfDeletion;
    }

    /**
     * Checks to see if the service provider's entered credentials exists in the collection.
     *
     * @param string $username the username that the service provider entered.
     * @param string $password the password that the service provider entered.
     * @return array notifies the client side if the service provider has a document in the collection.
     */
    public function authenticateServiceProvider($username, $password) {
        global $resultOfExistence;

        $findQuery = array('username' => $username);

        $mongoDBDriver = new MongoDBDriver();

        $cursor = $mongoDBDriver->findDocument($findQuery);

        if ($cursor->count() == 0){
            $resultOfExistence = array('success' => 0,
                                       'message' => "Invalid login");
        } else if ($cursor == null) {
            $resultOfExistence = array('success' => 0,
                                       'message' => "Connection error");
        } else {
            $document = $cursor->current();

            $hashedPassword = $document['password'];

            $hashUtility = new HashUtility();

            if ($hashUtility->checkPassword($hashedPassword, $password)) {
                $resultOfExistence = array('success' => 1,
                                           'message' => "User exists");
            } else {
                $resultOfExistence = array('success' => 0,
                                           'message' => "Invalid login");
            }
        }

        return $resultOfExistence;
    }
}