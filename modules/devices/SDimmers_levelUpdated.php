<?php

/*
 $tm=time();
 $this->setProperty('updated', $tm);
 $this->setProperty('updatedText', date('H:i', $tm));
*/

$level = $this->getProperty('level');
$minWork = $this->getProperty('minWork');
$maxWork = $this->getProperty('maxWork');

$statusUpdated = 0;

if ($level > 0) {
    $this->setProperty('levelSaved', $level);
    if (!$this->getProperty('status')) {
        $statusUpdated = 1;
        $this->setProperty('status', 1, false);
    }
    if ($minWork != $maxWork) {
        DebMes("Level updated to " . $level, 'dimming');
        $levelWork = round($minWork + round(($maxWork - $minWork) * $level / 100));
        if ($this->getProperty('levelWork') != $levelWork) {
            DebMes("Setting new levelWork to " . (int)$levelWork, 'dimming');
            $this->setProperty('levelWork', (int)$levelWork);
        }
    }
} else {
    if ($this->getProperty('status')) {
        $statusUpdated = 1;
        $this->setProperty('status', 0);
    }
    $this->setProperty('levelWork', (int)$minWork);
}

if (!$statusUpdated) {
    $this->callMethod('logicAction');
    include_once(DIR_MODULES . 'devices/devices.class.php');
    $dv = new devices();
    $dv->checkLinkedDevicesAction($this->object_title, $level);
}