<?php

namespace App\Helpers;

class User
{
    private $id;
    private $name;
    private $contactNumber;
    private $emailId;
    private $unitName;
    private $zoneName;
    private $divisionName;
    private $dob;
    private $gender;
    private $status;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getContactNumber()
    {
        return $this->contactNumber;
    }

    /**
     * @param mixed $contactNumber
     */
    public function setContactNumber($contactNumber): void
    {
        $this->contactNumber = $contactNumber;
    }

    /**
     * @return mixed
     */
    public function getEmailId()
    {
        return $this->emailId;
    }

    /**
     * @param mixed $emailId
     */
    public function setEmailId($emailId): void
    {
        $this->emailId = $emailId;
    }

    /**
     * @return mixed
     */
    public function getUnitName()
    {
        return $this->unitName;
    }

    /**
     * @param mixed $unitName
     */
    public function setUnitName($unitName): void
    {
        $this->unitName = $unitName;
    }

    /**
     * @return mixed
     */
    public function getZoneName()
    {
        return $this->zoneName;
    }

    /**
     * @param mixed $zoneName
     */
    public function setZoneName($zoneName): void
    {
        $this->zoneName = $zoneName;
    }

    /**
     * @return mixed
     */
    public function getDivisionName()
    {
        return $this->divisionName;
    }

    /**
     * @param mixed $divisionName
     */
    public function setDivisionName($divisionName): void
    {
        $this->divisionName = $divisionName;
    }

    /**
     * @return mixed
     */
    public function getDob()
    {
        return $this->dob;
    }

    /**
     * @param mixed $dob
     */
    public function setDob($dob): void
    {
        $this->dob = $dob;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     */
    public function setGender($gender): void
    {
        $this->gender = $gender;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }


}