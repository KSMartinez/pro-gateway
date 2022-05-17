<?php

namespace App\Model;

use App\Entity\Group;

class GroupDemand
{
    private Group $group;
    private string $notificationMessage;

    /**
     * @return Group
     */
    public function getGroup(): Group
    {
        return $this->group;
    }

    /**
     * @param Group $group
     * @return GroupDemand
     */
    public function setGroup(Group $group): self
    {
        $this->group = $group;
        return $this;
    }

    /**
     * @return string
     */
    public function getNotificationMessage(): string
    {
        return $this->notificationMessage;
    }

    /**
     * @param string $notificationMessage
     * @return GroupDemand
     */
    public function setNotificationMessage(string $notificationMessage): self
    {
        $this->notificationMessage = $notificationMessage;
        return $this;
    }
}