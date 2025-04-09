<?php
class User {
    private $id;                  
    private $name;                
    private $email;               
    private $passwordHash;        
    private $userType;            // User type: 'admin', 'organization', 'learner'
    private $subscriptionPlanId;  
    private $organizationId;      
    private $isBanned;            
    private $score;               
    private $createdAt;           

    // Constructor
    public function __construct($id, $name, $email, $passwordHash, $userType, $subscriptionPlanId, $organizationId, $isBanned, $score, $createdAt) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->userType = $userType;
        $this->subscriptionPlanId = $subscriptionPlanId;
        $this->organizationId = $organizationId;
        $this->isBanned = $isBanned;
        $this->score = $score;
        $this->createdAt = $createdAt;
    }

    // Getter methods
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPasswordHash() {
        return $this->passwordHash;
    }

    public function getUserType() {
        return $this->userType;
    }

    public function getSubscriptionPlanId() {
        return $this->subscriptionPlanId;
    }

    public function getOrganizationId() {
        return $this->organizationId;
    }

    public function getIsBanned() {
        return $this->isBanned;
    }

    public function getScore() {
        return $this->score;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    // Setter methods
    public function setName($name) {
        $this->name = $name;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setPasswordHash($passwordHash) {
        $this->passwordHash = $passwordHash;
    }

    public function setUserType($userType) {
        $this->userType = $userType;
    }

    public function setSubscriptionPlanId($subscriptionPlanId) {
        $this->subscriptionPlanId = $subscriptionPlanId;
    }

    public function setOrganizationId($organizationId) {
        $this->organizationId = $organizationId;
    }

    public function setIsBanned($isBanned) {
        $this->isBanned = $isBanned;
    }

    public function setScore($score) {
        $this->score = $score;
    }

    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
    }
}
?>
