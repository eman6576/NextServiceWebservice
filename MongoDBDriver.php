<?php

/**
 * Handles the connection to the database and the CRUD operations.
 *
 * Created by PhpStorm.
 * User: Emanuel Guerrero
 * Date: 2/10/16
 * Time: 9:38 PM
 */
class MongoDBDriver {
    private $connectionStatus;
    private $dataBaseName;
    private $collectionName;

    /**
     * Constructs an instance of MongoDBDriver.
     */
    public function _construct() {
       $this->connectionStatus = false;
        $this->dataBaseName = "nextservice";
        $this->collectionName = "useraccount";
    }

    /**
     * Deallocate the instance of MongoDBDriver and does clean up.
     */
    public function _destruct() {
        $this->connectionStatus = null;
        $this->dataBaseName = null;
        $this->collectionName = null;
    }

    /**
     * Connects to the the nextservice database.
     *
     * @return MongoClient an instance of the nextservice MongoDB system for accessing different collections.
     */
    private function connectToMongoDB() {
        global $mongoClient;

        try {
            $mongoClient = new MongoClient();

            $this->connectionStatus = true;
        }
        catch (MongoConnectionException $e) {
            $this->connectionStatus = false;
        } catch (MongoException $e) {
            $this->connectionStatus = false;
        }

        return $mongoClient;
    }

    /**
     * Gets the status of the connection to the database.
     *
     * @return mixed boolean value indicating if the mongoClient is connected.
     */
    private function getConnectionStatus() {
        return $this->connectionStatus;
    }

    /**
     * Inserts a document into the collection.
     *
     * @param array $document a document object in JSON format.
     * @return bool if the insertion of the document was successful.
     */
    public function insertDocument($document = array()) {
        try {
            $mongoClient = $this->connectToMongoDB();

            if ($this->getConnectionStatus() == true) {
                $mongoDataBase = $mongoClient->selectDB($this->dataBaseName);

                $userAccountCollection = $mongoDataBase->selectCollection($this->collectionName);
                $userAccountCollection->insert($document);
                $userAccountCollection->drop();

                $mongoDataBase->drop();

                $mongoClient->close();
            } else {
                return false;
            }
        } catch (MongoException $e) {
            return false;
        }

        return true;
    }

    /**
     * Queries the collection based on the query array.
     *
     * @param array $findQuery the fields to query the collection.
     * @return MongoCursor a cursor object that contains the documents returned from the query.
     */
    public function findDocument($findQuery = array()) {
        global $cursor;

        try {
            $mongoClient = $this->connectToMongoDB();

            if ($this->getConnectionStatus() == true) {
                $mongoDataBase = $mongoClient->selectDB($this->dataBaseName);

                $userAccountCollection = $mongoDataBase->selectCollection($this->collectionName);

                $cursor = $userAccountCollection->find($findQuery);

                $userAccountCollection->drop();

                $mongoDataBase->drop();

                $mongoClient->close();
            } else {
                $cursor = null;
            }
        } catch (MongoException $e) {
            return $cursor = null;
        }

        return $cursor;
    }

    /**
     * Updates a specific document in the collection based on the username.
     *
     * @param array $findQuery the fields to query the collection for the specific document.
     * @param array $updatedUserAccountDocument contains the new information of the service provider.
     * @return bool if the update of the document was successful.
     */
    public function updateDocument($findQuery = array(), $updatedUserAccountDocument = array()) {
        try {
            $mongoClient = $this->connectToMongoDB();

            if ($this->getConnectionStatus() == true) {
                $mongoDataBase = $mongoClient->selectDB($this->dataBaseName);

                $userAccountCollection = $mongoDataBase->selectCollection($this->collectionName);

                $userAccountDocument = $userAccountCollection->findOne($findQuery);
                $userAccountDocument['username'] = $updatedUserAccountDocument['username'];
                $userAccountDocument['password'] = $updatedUserAccountDocument['password'];
                $userAccountDocument['firstname'] = $updatedUserAccountDocument['firstname'];
                $userAccountDocument['lastname'] = $updatedUserAccountDocument['lastname'];
                $userAccountDocument['streetaddress'] = $updatedUserAccountDocument['streetaddress'];
                $userAccountDocument['city'] = $updatedUserAccountDocument['city'];
                $userAccountDocument['state'] = $updatedUserAccountDocument['state'];
                $userAccountDocument['zipcode'] = $updatedUserAccountDocument['zipcode'];
                $userAccountDocument['baseservice'] = $updatedUserAccountDocument['baseservice'];
                $userAccountDocument['subservices'] = $updatedUserAccountDocument['subservices'];
                $userAccountDocument['description'] = $updatedUserAccountDocument['description'];

                $userAccountCollection->save($userAccountDocument);
                $userAccountCollection->drop();

                $mongoDataBase->drop();

                $mongoClient->close();
            }
        } catch (MongoException $e) {
            return false;
        }

        return true;
    }

    /**
     * Deletes a specific document in the collection based on the username.
     *
     * @param array $findQuery the fields to query the collection for the specific document.
     * @return bool if the deletion of the document was successful.
     */
    public function deleteDocument($findQuery = array()) {
        try {
            $mongoClient = $this->connectToMongoDB();

            if ($this->getConnectionStatus() == true) {
                $mongoDateBase = $mongoClient->selectDB($this->dataBaseName);

                $userAccountCollection = $mongoDateBase->selectCollection($this->collectionName);
                $userAccountCollection->remove($findQuery);
                $userAccountCollection->drop();

                $mongoDateBase->drop();

                $mongoClient->close();
            }
        } catch (MongoException $e) {
            return false;
        }

        return true;
    }
}