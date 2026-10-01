<?php
namespace App\Interfaces;

interface LeadEmailProcessingStrategy
{
   
    public function process($message);
}