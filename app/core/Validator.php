<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Validador simples por regras declarativas.
 *
 * Regras suportadas:
 *   required, email, min:N, max:N, numeric, integer, in:a,b,c,
 *   phone, slug, confirmed, url, boolean
 *
 * Uso:
 *   $v = new Validator($data, [
 *       'name'  => 'required|max:120',
 *       'email' => 'required|email|max:160',
 *   ], ['name' => 'Nome']);
 *   if ($v->fails()) { $errors = $v->errors(); }
 */
final class Validator
{
    private array $data;
    private array $rules;
    private array $labels;
    private array $errors = [];

    public function __construct(array $data, array $rules, array $labels = [])
    {
        $this->data = $data;
        $this->rules = $rules;
        $this->labels = $labels;
        $this->validate();
    }

    private function label(string $field): string
    {
        return $this->labels[$field] ?? ucfirst(str_replace('_', ' ', $field));
    }

    private function validate(): void
    {
        foreach ($this->rules as $field => $ruleset) {
            $rules = is_array($ruleset) ? $ruleset : explode('|', $ruleset);
            $value = $this->data[$field] ?? null;
            $valueStr = is_string($value) ? trim($value) : $value;

            $isRequired = in_array('required', $rules, true);
            $isEmpty = $valueStr === null || $valueStr === '' || (is_array($valueStr) && count($valueStr) === 0);

            if ($isEmpty && !$isRequired) {
                continue; // campos opcionais vazios não são validados
            }

            foreach ($rules as $rule) {
                [$name, $param] = array_pad(explode(':', $rule, 2), 2, null);
                $this->applyRule($field, $name, $param, $valueStr);
            }
        }
    }

    private function applyRule(string $field, string $rule, ?string $param, mixed $value): void
    {
        $label = $this->label($field);

        switch ($rule) {
            case 'required':
                if ($value === null || $value === '' || (is_array($value) && count($value) === 0)) {
                    $this->add($field, "O campo {$label} é obrigatório.");
                }
                break;
            case 'email':
                if (!filter_var((string) $value, FILTER_VALIDATE_EMAIL)) {
                    $this->add($field, "Informe um e-mail válido.");
                }
                break;
            case 'url':
                if (!filter_var((string) $value, FILTER_VALIDATE_URL)) {
                    $this->add($field, "O campo {$label} deve ser uma URL válida.");
                }
                break;
            case 'min':
                if (mb_strlen((string) $value) < (int) $param) {
                    $this->add($field, "O campo {$label} deve ter ao menos {$param} caracteres.");
                }
                break;
            case 'max':
                if (mb_strlen((string) $value) > (int) $param) {
                    $this->add($field, "O campo {$label} deve ter no máximo {$param} caracteres.");
                }
                break;
            case 'numeric':
                if (!is_numeric($value)) {
                    $this->add($field, "O campo {$label} deve ser numérico.");
                }
                break;
            case 'integer':
                if (filter_var($value, FILTER_VALIDATE_INT) === false) {
                    $this->add($field, "O campo {$label} deve ser um número inteiro.");
                }
                break;
            case 'in':
                $allowed = explode(',', (string) $param);
                if (!in_array((string) $value, $allowed, true)) {
                    $this->add($field, "Valor inválido para {$label}.");
                }
                break;
            case 'phone':
                if (!preg_match('/^[0-9()+\-\s]{8,20}$/', (string) $value)) {
                    $this->add($field, "Informe um telefone válido.");
                }
                break;
            case 'slug':
                if (!preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', (string) $value)) {
                    $this->add($field, "O campo {$label} deve conter apenas letras minúsculas, números e hífens.");
                }
                break;
            case 'boolean':
                if (!in_array((string) $value, ['0', '1', 'true', 'false', 'on', 'off'], true)) {
                    $this->add($field, "O campo {$label} é inválido.");
                }
                break;
            case 'confirmed':
                $confirmField = $field . '_confirmation';
                if (($this->data[$confirmField] ?? null) !== $value) {
                    $this->add($field, "A confirmação de {$label} não confere.");
                }
                break;
        }
    }

    private function add(string $field, string $message): void
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = $message; // guarda o primeiro erro por campo
        }
    }

    public function fails(): bool
    {
        return $this->errors !== [];
    }

    public function passes(): bool
    {
        return $this->errors === [];
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function firstError(): ?string
    {
        return $this->errors === [] ? null : reset($this->errors);
    }
}
