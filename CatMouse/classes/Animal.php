<?php
namespace Application\CatMouse\classes;

use Application\CatMouse\models\Field;
use \SplObjectStorage;

abstract class Animal
{
    public $name;
    CONST RAND_FOR_WALK = 1;
    private $fieldOfVision;
    private $cruisingRange;
    private $location = ["x" => 0, "y" => 0];
    private $seeAnimals;
    private $hashOfAnimal;
    private $naturalEnemies;
    private $fieldSize;
    private $field;

    public function __construct($fieldOfVision, $cruisingRange, $name,  Field $field)
    {
        $this->fieldOfVision = $fieldOfVision;
        $this->cruisingRange = $cruisingRange;
        $this->name = $name;
        $this->hashOfAnimal = spl_object_hash($this);
        $this->fieldSize = $field->getSize();
        $this->field = $field;
    }

    public function getCruisingRange()
    {
        return $this->cruisingRange;
    }

    public function setCruisingRange($cruisingRange)
    {
        $this->cruisingRange = $cruisingRange;
    }

    public function getFieldOfVision()
    {
        return $this->fieldOfVision;
    }

    public function setFieldOfVision($fieldOfVision)
    {
        $this->fieldOfVision = $fieldOfVision;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setLocation($location)
    {
        $this->location = $location;
    }

    protected function getRangeView()
    {
        $location = $this->getLocation();
        $fieldOfVision = $this->getFieldOfVision();
        $xMin = $location["x"] - $fieldOfVision < 0 ? 0 : $location["x"] - $fieldOfVision;
        $xMax = $location["x"] + $fieldOfVision;
        $xRange = ["xMin" => $xMin, "xMax" => $xMax];

        $yMin = $location["y"] - $fieldOfVision < 0 ? 0 : $location["y"] - $fieldOfVision;
        $yMax = $location["y"] + $fieldOfVision;
        $yRange = ["yMin" => $yMin, "yMax" => $yMax];

        return ["x" => $xRange, "y" => $yRange];
    }

    public function lookAround()
    {
        $listOfAnimals = clone $this->getField()->getListOfAnimals();

        $seeAnimals = new SplObjectStorage();
        $range = $this->getRangeView();

        foreach ($listOfAnimals as $animal) {
            if ($animal === $this) continue;

            $animalLocation = $animal->getLocation();

            if (
                ($animalLocation["x"] >= $range["x"]["xMin"] && $animalLocation["x"] <= $range["x"]["xMax"])
                && ($animalLocation["y"] >= $range["y"]["yMin"] && $animalLocation["y"] <= $range["y"]["yMax"])
            ) {
                $seeAnimals->attach($animal);
            }
        }

        $this->setSeeAnimals($seeAnimals);
    }

    public function getSeeAnimals()
    {
        return $this->seeAnimals;
    }

    public function setSeeAnimals(SplObjectStorage $seeAnimals)
    {
        $this->seeAnimals = $seeAnimals;
    }

    public function getFieldSize()
    {
        return $this->fieldSize;
    }

    public function getField()
    {
        return $this->field;
    }

    protected function goUp($eat = false){
        $coordinatesCell["x"] = $this->location["x"];
        $coordinatesCell["y"] = $this->location["y"] - $this->cruisingRange < 1 ? 1 : $this->location["y"] - $this->cruisingRange;
        $cellContain = $this->checkCells($coordinatesCell);
        if(is_null($cellContain)){
            $this->location = $coordinatesCell;
        }
        if(!is_null($cellContain) && $eat == true){
            $this->location = $coordinatesCell;
        }
    }

    protected function goDown($eat = false){
        $coordinatesCell["x"] = $this->location["x"];
        $coordinatesCell["y"] = $this->location["y"] + $this->cruisingRange > $this->fieldSize ? $this->fieldSize : $this->location["y"] + $this->cruisingRange;
        $cellContain = $this->checkCells($coordinatesCell);
        if(is_null($cellContain)){
            $this->location = $coordinatesCell;
        }
        if(!is_null($cellContain) && $eat == true){
            $this->location = $coordinatesCell;
        }
    }

    protected function goLeft($eat = false){
        $coordinatesCell["x"] = $this->location["x"] - $this->cruisingRange < 1 ? 1 : $this->location["x"] - $this->cruisingRange;
        $coordinatesCell["y"] = $this->location["y"];
        $cellContain = $this->checkCells($coordinatesCell);
        if(is_null($cellContain)){
            $this->location = $coordinatesCell;
        }
        if(!is_null($cellContain) && $eat == true){
            $this->location = $coordinatesCell;
        }
    }

    protected function goRight($eat = false){
        $coordinatesCell["x"] = $this->location["x"] + $this->cruisingRange > $this->fieldSize ? $this->fieldSize : $this->location["x"] + $this->cruisingRange;
        $coordinatesCell["y"] = $this->location["y"];
        $cellContain = $this->checkCells($coordinatesCell);
        if(is_null($cellContain)){
            $this->location = $coordinatesCell;
        }
        if(!is_null($cellContain) && $eat == true){
            $this->location = $coordinatesCell;
        }
    }

    protected function goUpAndRight($eat = false){
        $coordinatesCell["x"] = $this->location["x"] + $this->cruisingRange > $this->fieldSize ? $this->fieldSize : $this->location["x"] + $this->cruisingRange;
        $coordinatesCell["y"] = $this->location["y"] - $this->cruisingRange < 1 ? 1 : $this->location["y"] - $this->cruisingRange;
        $cellContain = $this->checkCells($coordinatesCell);
        if(is_null($cellContain)){
            $this->location = $coordinatesCell;
        }
        if(!is_null($cellContain) && $eat == true){
            $this->location = $coordinatesCell;
        }
    }

    protected function goUpAndLeft($eat = false){
        $coordinatesCell["x"] = $this->location["x"] - $this->cruisingRange < 1 ? 1 : $this->location["x"] - $this->cruisingRange;
        $coordinatesCell["y"] = $this->location["y"] - $this->cruisingRange < 1 ? 1 : $this->location["y"] - $this->cruisingRange;
        $cellContain = $this->checkCells($coordinatesCell);
        if(is_null($cellContain)){
            $this->location = $coordinatesCell;
        }
        if(!is_null($cellContain) && $eat == true){
            $this->location = $coordinatesCell;
        }
    }

    protected function goDownAndRight($eat = false){
        $coordinatesCell["x"] = $this->location["x"] + $this->cruisingRange > $this->fieldSize ? $this->fieldSize : $this->location["x"] + $this->cruisingRange;
        $coordinatesCell["y"] = $this->location["y"] + $this->cruisingRange > $this->fieldSize ? $this->fieldSize : $this->location["y"] + $this->cruisingRange;
        $cellContain = $this->checkCells($coordinatesCell);
        if(is_null($cellContain)){
            $this->location = $coordinatesCell;
        }
        if(!is_null($cellContain) && $eat == true){
            $this->location = $coordinatesCell;
        }
    }

    protected function goDownAndLeft($eat = false){
        $coordinatesCell["x"] = $this->location["x"] - $this->cruisingRange < 1 ? 1 : $this->location["x"] - $this->cruisingRange;
        $coordinatesCell["y"] = $this->location["y"] + $this->cruisingRange > $this->fieldSize ? $this->fieldSize : $this->location["y"] + $this->cruisingRange;
        $cellContain = $this->checkCells($coordinatesCell);
        if(is_null($cellContain)){
            $this->location = $coordinatesCell;
        }
        if(!is_null($cellContain) && $eat == true){
            $this->location = $coordinatesCell;
        }
    }

    // Проверить ячейку
    protected function checkCells($coordinatesCell){
        $list = $this->getField()->getListOfAnimals();
        foreach ($list as $animal){
            $coordinates = $animal->getLocation();
            if($coordinates["x"] == $coordinatesCell["x"] && $coordinates["y"] == $coordinatesCell["y"]){
                return $animal;
            }
        }
        return null;
    }

    abstract public function walk();

    abstract protected function randomWalk();

    abstract public function getLabel();


} 