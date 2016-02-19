<?php

include "MongoDBDriver.php";

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
                $document = array('username' => $username,
                                  'password' => $password,
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
        global $resultOfInsertion;

        $findQuery = array('zipcode' => $zipCode,
                           'baseservice' => $baseService,
                           'subservices' => $subServices);

        $mongoDBDriver = new MongoDBDriver();

        $cursor = $mongoDBDriver->findDocument($findQuery);

        if ($cursor == null) {
            $resultOfInsertion = array('success' => 0,
                                       'message' => "Query error");
        } else {
            $resultOfInsertion = array('success' => 1,
                                       'message' => "Service providers found",
                                        'serviceproviders' => $cursor);
        }

        return $resultOfInsertion;
    }

    public function updateServiceProvider($username, $password, $firstName, $lastName, $streetAddress, $city,
                                          $state, $zipCode, $baseService, $subServices = array(), $description) {
        global $resultOfInsertion;

        $updateDocument = array('username' => $username,
                                'password' => $password,
                                'firstname' => $firstName,
                                'lastname' => $lastName,
                                'streetaddress' => $streetAddress,
                                'city' => $city,
                                'state' => $state,
                                'zipcode' => $zipCode,
                                'baseservice' => $baseService,
                                'subservices' => $subServices,
                                'description' => $description);

        $mongoDBDriver = new MongoDBDriver();
    }

    public function deleteServiceProvider() {

    }
}