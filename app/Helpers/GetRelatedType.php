<?php
if (!function_exists('getRelatedType')) {
    function getRelatedType(int $type)
    {
        if ($type == 1) {
            return response()->json(['error' => 'Wybrane mieszkanie zostało już przypisane'], 422);
        } elseif ($type == 2) {
            return response()->json(['error' => 'Wybrana komórka lokatorska została już przypisana'], 422);
        } elseif ($type == 3) {
            return response()->json(['error' => 'Wybrane miejsce parkingowe zostało już przypisane'], 422);
        }
        return null;
    }
}