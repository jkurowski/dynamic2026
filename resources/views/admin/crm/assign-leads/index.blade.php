@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-head container-fluid">
                <div class="row">
                    <div class="col-6 pl-0">
                        <h4 class="page-title"><i class="fe-save"></i>Przypisywanie automatyczne</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-header border-bottom card-nav">
            <nav class="nav">
                <a class="nav-link {{ Request::routeIs('admin.crm.inbox.*') ? ' active' : '' }}"
                    href="{{ route('admin.crm.inbox.index') }}"><span class="fe-check-square"></span>Wszystkie</a>
                <a class="nav-link {{ Request::routeIs('admin.externalLeads*') ? ' active' : '' }}"
                    href="{{ route('admin.externalLeads.index') }}"><span class="fe-external-link"></span>Zewnętrzne</a>
                <a class="nav-link {{ Request::routeIs('admin.crm.assign-leads.*') ? ' active' : '' }}"
                    href="{{ route('admin.crm.assign-leads.index') }}"><span class="fe-save"></span>Przypisywanie
                    automatyczne</a>
            </nav>
        </div>

        <div class="card mt-3 p-4">
            <div id="query-builder" class="query-builder"></div>
            <div class="text-end">
                <button id="save-query" class="btn btn-primary mt-3">Zapisz</button>
            </div>
        </div>
    </div>
@endsection


<style>
    .rules-list li::marker {
        color: transparent;
    }

    .rules-list {
        outline: 1px solid var(--bs-gray-300);
        padding: 1rem;
        margin-bottom: 0;
    }

    .rule-container {
        display: flex;
        gap: 0.5rem;
        margin: 0.5rem 0;
    }

    .query-builder :is(select, input) {
        width: auto;
        display: initial;
    }
</style>


@push('scripts')
    <script defer>
        const saveEndpointURL = '{{ route('admin.crm.assign-leads.store') }}'
        const initialValues = @json($initialValues);
        const investments = @json($investments);
        investments.unshift({
            value: null,
            label: 'Wybierz inwestycję'
        });

        class QueryBuilder {
            constructor(data) {
                this.condition = data.condition;
                this.rules = data.rules.map(rule =>
                    rule.condition ? new QueryBuilder(rule) : new Rule(rule)
                );
            }
            toJSON() {
                return {
                    condition: this.condition,
                    rules: this.rules.map(rule => rule.toJSON())
                };
            }
        }

        class Rule {
            constructor({
                id,
                field,
                type,
                input,
                operator,
                value,
                investment_id
            }) {
                Object.assign(this, {
                    id,
                    field,
                    type,
                    input,
                    operator,
                    value,
                    investment_id
                });
            }
            toJSON() {
                return {
                    ...this
                };
            }
        }

        class QueryBuilderUI {
            constructor(containerId, saveButtonId, initialValues) {
                this.container = document.getElementById(containerId);
                this.saveButton = document.getElementById(saveButtonId);
                this.queryBuilder = this.createQueryBuilder(initialValues);
                this.fieldOptions = [{
                        value: 'investment_name',
                        label: 'Nazwa inwestycji'
                    },

                ]; // Add more fields as needed
                this.operatorOptions = [{
                        value: 'contains',
                        label: 'Zawiera'
                    },
                    {
                        value: 'not_contains',
                        label: 'Nie zawiera'
                    },
                    {
                        value: 'equal',
                        label: 'Równy'
                    },
                    {
                        value: 'not_equal',
                        label: 'Nie równy'
                    },
                ];
                // Add more operators as needed #Not used right now
                this.conditionOptions = [{
                        value: 'AND',
                        label: 'AND'
                    },
                    {
                        value: 'OR',
                        label: 'OR'
                    }
                ];
            }

            createQueryBuilder(initialValues) {
                if (initialValues) {
                    return new QueryBuilder(initialValues);
                }
                return new QueryBuilder({
                    condition: 'AND',
                    rules: []
                });
            }

            init() {
                this.renderQueryBuilder(this.container, this.queryBuilder);
                this.setupSaveButton();
            }

            renderQueryBuilder(container, query, parentQuery = null) {
                container.innerHTML = '';
                // this.renderConditionSelect(container, query, parentQuery);
                this.renderRulesList(container, query, parentQuery);
                this.renderButtons(container, query, parentQuery);
            }

            renderConditionSelect(container, query, parentQuery) {
                const conditionSelect = this.createSelect('condition', this.conditionOptions, query.condition);
                conditionSelect.className = 'form-select mt-4 mb-2';
                conditionSelect.addEventListener('change', (e) => {
                    query.condition = e.target.value;
                    this.renderQueryBuilder(container, query, parentQuery);
                });
                container.appendChild(conditionSelect);

                if (parentQuery) {
                    this.renderDeleteGroupButton(container, query, parentQuery);
                }
            }

            renderDeleteGroupButton(container, query, parentQuery) {
                const deleteGroupButton = this.createButton('Usuń grupę', 'btn btn-danger ms-2');
                deleteGroupButton.addEventListener('click', () => {
                    const index = parentQuery.rules.indexOf(query);
                    if (index > -1) {
                        parentQuery.rules.splice(index, 1);
                        this.renderQueryBuilder(this.container, this.queryBuilder);
                    }
                });
                container.appendChild(deleteGroupButton);
            }

            renderRulesList(container, query) {
                const rulesList = document.createElement('ul');
                rulesList.className = 'rules-list';
                query.rules.forEach((rule, index) => {
                    const li = document.createElement('li');
                    if (rule instanceof QueryBuilder) {
                        this.renderQueryBuilder(li, rule, query);
                    } else {
                        this.renderRule(li, rule, query, index);
                    }
                    rulesList.appendChild(li);
                });
                container.appendChild(rulesList);
            }

            renderRule(container, rule, query, index) {
                const ruleContainer = document.createElement('div');
                ruleContainer.className = 'rule-container';

                const fieldSelect = this.createSelect('field', this.fieldOptions, rule.field);
                const operatorSelect = this.createSelect('operator', this.operatorOptions, rule.operator);
                const valueInput = this.createInput('text', 'value', rule.value);
                const investmentSelect = this.createSelect('investment_id', investments, rule.investment_id);
                const p = document.createElement('p');
                p.className = 'd-flex align-items-center justify-content-center px-2';
                p.textContent = 'Przypisz do';


                fieldSelect.addEventListener('change', (e) => rule.field = e.target.value);
                operatorSelect.addEventListener('change', (e) => rule.operator = e.target.value);
                valueInput.addEventListener('input', (e) => rule.value = e.target.value);
                investmentSelect.addEventListener('change', (e) => rule.investment_id = e.target.value);

                const deleteButton = this.createButton('Usuń', 'btn btn-danger', '<i class="fe-trash"></i>');
                deleteButton.addEventListener('click', () => {
                    query.rules.splice(index, 1);
                    this.renderQueryBuilder(this.container, this.queryBuilder);
                });


                ruleContainer.append(fieldSelect, operatorSelect, valueInput, p, investmentSelect, deleteButton);
                container.appendChild(ruleContainer);
            }

            renderButtons(container, query) {
                const buttonContainer = document.createElement('div');
                buttonContainer.className = 'button-container mt-2';

                const addRuleButton = this.createButton('Dodaj regułę', 'btn btn-primary me-2');
                addRuleButton.addEventListener('click', () => {
                    query.rules.push(new Rule({
                        id: `rule-${Date.now()}`,
                        field: this.fieldOptions[0].value,
                        type: 'string',
                        input: 'select',
                        operator: 'equal',
                        value: '',
                        investment_id: null

                    }));
                    this.renderQueryBuilder(this.container, this.queryBuilder);
                });

                // const addGroupButton = this.createButton('Dodaj grupę', 'btn btn-primary');
                // addGroupButton.addEventListener('click', () => {
                //     query.rules.push(new QueryBuilder({
                //         condition: 'AND',
                //         rules: [{
                //             id: `rule-${Date.now()}`,
                //             field: 'name',
                //             type: 'string',
                //             input: 'text',
                //             operator: 'equal',
                //             value: ''
                //         }]
                //     }));
                //     this.renderQueryBuilder(this.container, this.queryBuilder);
                // });

                // buttonContainer.append(addRuleButton, addGroupButton);
                buttonContainer.append(addRuleButton);
                container.appendChild(buttonContainer);
            }

            async makeSaveRequest() {
                const response = await fetch(saveEndpointURL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify(this.queryBuilder)
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'An error occurred');
                }

                return data;
            }

            setupSaveButton() {
                this.saveButton.addEventListener('click', async () => {
                    if (this.isValid(this.queryBuilder)) {
                        this.saveButton.disabled = true;
                        this.saveButton.innerHTML = 'Zapisywanie...';
                        try {
                            const responseData = await this.makeSaveRequest();
                            toastr.success(responseData.message);
                        } catch (error) {
                            console.error('Save request error:', error);
                            if (error.errors) {
                                // Display validation errors
                                Object.values(error.errors).forEach(errorMessages => {
                                    errorMessages.forEach(message => toastr.error(message));
                                });
                            } else {
                                toastr.error(error.message || 'Wystąpił błąd podczas zapisywania');
                            }
                        } finally {
                            this.saveButton.disabled = false;
                            this.saveButton.innerHTML = 'Zapisz';
                        }
                    } else {
                        toastr.error(
                            'Nie można zapisać: Niektóre grupy nie mają reguł. Proszę dodać reguły do wszystkich grup lub usunąć puste grupy.'
                        );
                    }
                });
            }

            isValid(query) {
                if (query.rules.length === 0) {
                    return false;
                }

                for (const rule of query.rules) {
                    if (rule instanceof QueryBuilder) {
                        if (!this.isValid(rule)) {
                            return false;
                        }
                    }
                }

                return true;
            }

            createSelect(name, options, value) {
                const select = document.createElement('select');
                select.className = 'form-select';
                select.name = name;
                options.forEach(option => {
                    const optionElement = document.createElement('option');
                    if (typeof option === 'object' && option.value !== undefined) {
                        optionElement.value = option.value;
                        optionElement.textContent = option.label || option.value;
                    } else {
                        optionElement.value = option;
                        optionElement.textContent = option;
                    }
                    optionElement.selected = optionElement.value === value;
                    select.appendChild(optionElement);
                });
                return select;
            }

            createInput(type, name, value) {
                const input = document.createElement('input');
                input.type = type;
                input.className = 'form-control';
                input.name = name;
                input.value = value;
                return input;
            }

            createButton(text, className, icon = null) {
                const button = document.createElement('button');
                button.textContent = text;
                button.className = className;
                if(icon) {
                    button.textContent = '';
                    button.innerHTML = icon;
                }
                return button;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const queryBuilderUI = new QueryBuilderUI('query-builder', 'save-query', initialValues);
            queryBuilderUI.init();
        });
    </script>
@endpush
