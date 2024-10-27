<?php

namespace App\Entity\Trait;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

trait TimestampableTrait
{
    #[ORM\Column(type: "datetime", name: "created_at")]
    #[Gedmo\Timestampable(on: "create")]
    public $createdAt = null;

    #[ORM\Column(type: "datetime", name: "updated_at")]
    #[Gedmo\Timestampable(on: "update")]
    public $updatedAt;

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }
}
