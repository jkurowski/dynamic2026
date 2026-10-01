<?php
if (!function_exists('floorToSelect')) {
    /**
     * Generate a select dropdown for floor values from a relationship.
     *
     * @param Illuminate\Database\Eloquent\Collection|array $floors Collection or array of floor objects.
     * @param string $valueField The field in the floor object to use as the value (e.g., 'id', 'name').
     * @param string $labelField The field in the floor object to display as the label (e.g., 'name').
     * @return string HTML for the select dropdown.
     */
    function floorToSelect($floors, string $valueField = 'id', string $labelField = 'name'): string
    {
        $html = '';

        // Sort floors by 'position'
        $floors = $floors->sortBy('position');

        foreach ($floors as $floor) {
            // Filter by type
            if ($floor->type == 1) {
                $value = htmlspecialchars($floor->$valueField);
                $label = htmlspecialchars($floor->$labelField);

                // Check if building_id is not 0
                if ($floor->building_id != 0 && isset($floor->building)) {
                    $buildingName = htmlspecialchars($floor->building->name);
                    $label = $buildingName . ' - ' . $label;
                }

                $selected = request()->input('floor') == $value ? ' selected' : '';
                $html .= '<option value="' . $value . '"' . $selected . '>';
                $html .= $label;
                $html .= '</option>';
            }
        }

        return $html;
    }
}