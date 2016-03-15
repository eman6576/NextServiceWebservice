<?php
/**
 * Creates and checks login in information a service provider enters.
 *
 * Created by PhpStorm.
 * User: Emanuel
 * Date: 2/21/16
 * Time: 11:26 PM
 */
class HashUtility {
    private $algorithm = '$2a';
    private $cost = '$10';

    /**
     * Creates and returns a unique salt used to hash the entered credential.
     *
     * @return string a unique salt used for hashing.
     */
    private function uniqueSalt() {
        return substr(sha1(mt_rand()),0,22);
    }

    /**
     * Salts and hashes the entered credential.
     *
     * @param string $enteredCredential the password of the service provider.
     * @return string the salted and hashed password.
     */
    public function hash($enteredCredential) {
        return crypt($enteredCredential, $this->algorithm . $this->cost . '$' . $this->uniqueSalt());
    }

    public function checkPassword($hashedPassword, $password) {
        $fullSalt = substr($hashedPassword, 0, 29);

        $newHashedPassword = crypt($password, $fullSalt);

        return ($hashedPassword == $newHashedPassword);
    }
}