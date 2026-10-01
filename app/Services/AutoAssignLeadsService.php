<?php

namespace App\Services;

use App\Models\ClientMessage;

class AutoAssignLeadsService
{

    private ?array $conditions;
    private ClientMessage $clientMessage;

    private const OPERATORS = ['contains', 'not_contains', 'equal', 'not_equal'];


    public function __construct(ClientMessage $clientMessage)
    {
        $this->conditions = settings('assign_leads');
        $this->clientMessage = $clientMessage;
    }

    public function getConditions(): ?array
    {
        return $this->conditions;
    }

    public function getClientMessage(): ClientMessage
    {
        return $this->clientMessage;
    }

    /**
     * Assign lead to investment 
     * @param int $investmentId - id of investment
     * @return bool - true on success, false if not
     */
    public function assignToInvestment(int $investmentId): bool
    {
        $arguments = $this->clientMessage->arguments;

        if (!$arguments) {
            return false;
        }

        $arguments = json_decode($arguments, true);
        $arguments['assigned_investment_id'] = $investmentId;

        $this->clientMessage->arguments = json_encode($arguments);

        $this->clientMessage->save();

        return true;
    }

    public function process()
    {
        if (!$this->conditions || !isset($this->conditions['rules'])) {
            return null;
        }

        $arguments = json_decode($this->clientMessage->arguments, true);
        if (!$arguments) {
            return null;
        }

        foreach ($this->conditions['rules'] as $rule) {
            $field = $rule['field'];
            $operator = $rule['operator'];
            $value = $rule['value'];

            if (!$this->checkCondition($arguments, $field, $operator, $value)) {
                continue;
            }

            $investmentId = intval($rule['investment_id']);

            $this->assignToInvestment($investmentId);
        }
    }

    private function checkCondition(array $arguments, string $field, string $operator, $value): bool
    {
        $fieldValue = $arguments[$field] ?? null;

        if (!in_array($operator, self::OPERATORS)) {
            return false;
        }

        switch ($operator) {
            case 'contains':
                return strpos($fieldValue, $value) !== false;
            case 'not_contains':
                return strpos($fieldValue, $value) === false;
            case 'equal':
                return $fieldValue == $value;
            case 'not_equal':
                return $fieldValue != $value;
            default:
                return false;
        }
    }
}
