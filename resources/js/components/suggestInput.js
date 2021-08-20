window.suggestInput = function (data) {
    return {
        as_select: data.as_select,
        id: data.id,
        value_attr: data.value_attr,
        label_attr: data.label_attr,
        options: {},
        option_ids: [],
        filtered_option_ids: [],
        focused_option_index: null,
        // Value of hidden input with supplied name attribute
        current_value: data.init_value,
        // Value of the text input (same as current_value for non-select instances)
        current_input: data.init_input,
        // What is displayed on the select button
        current_label: data.init_label,
        show_input: !data.as_select,
        show_options: false,
        open: false,
        options_url: data.options_url,
        options_loading: false,
        fuse: null,
        init() {
            this.parseOptions(data.options);
            if (!this.options_url) {
                const fuseOptions = {
                    keys: ['simple_label'],
                    includeScore: true,
                    isCaseSensitive: true,
                    threshold: 0.35,
                };
                let optionsArr = Object.values(getAlpineObj(this.options));
                const myIndex = Fuse.createIndex(fuseOptions.keys, optionsArr);
                this.fuse = new Fuse(optionsArr, fuseOptions, myIndex);
                if (this.current_input) {
                    this.filterOptions(this.current_input, false);
                }
            }
        },
        parseOptions(options) {
            let optionsCount = options.length;
            for (let i = 0; i < optionsCount; ++i) {
                let option = options[i];
                let optionLabel, optionValue;
                if (typeof option === 'string') {
                    optionLabel = option;
                    optionValue = option;
                    optionID = i;
                } else if (typeof option === 'object') {
                    optionLabel = _.get(option, this.label_attr, '');
                    optionValue = _.get(option, this.value_attr, i);
                    optionID = _.get(option, 'id', i);
                } else {
                    continue;
                }
                if (optionValue === this.current_value) this.current_label = optionLabel;
                let optionObj = {
                    id: optionID,
                    value: optionValue,
                    label: optionLabel,
                    simple_label: simpleSearchStr(optionLabel),
                };
                this.options[optionID] = optionObj;
                this.option_ids.push(optionID);
                this.filtered_option_ids.push(optionID);
            }
        },
        filterOptions(input, doShowOptions) {
            if (this.options_url) {
                this.focused_option_index = null;
                this.filtered_option_ids = [];
                this.options = {};
                this.options_loading = true;
                axios.post('/api/orgs/search', {
                    search: input
                }).then(response => {
                    let options = _.get(response, 'data');
                    this.parseOptions(options);
                }).catch(error => {
                    alert(getReadableAxiosError(error));
                }).finally(() => {
                    this.options_loading = false;
                    this.afterFilterOptions(doShowOptions);
                });
            } else {
                input = simpleSearchStr(input);
                if (!input) {
                    this.filtered_option_ids = getAlpineObj(this.option_ids);
                } else {
                    let result = this.fuse.search(input);
                    this.filtered_option_ids = _.map(result, 'item.id');
                }
                this.afterFilterOptions(doShowOptions);
            }
        },
        afterFilterOptions(doShowOptions) {
            if (this.filtered_option_ids.length) {
                this.focused_option_index = 0;
            } else {
                this.focused_option_index = null;
            }
            if (doShowOptions) {
                this.show_options = true;
                this.$nextTick(() => { this.scrollToFocus() });
            }
        },
        scrollToFocus() {
            if (this.focused_option_index === null) return;
            let el = document.getElementById(this.id + '-option-' + this.focused_option_index);
            if (!el) return;
            el.scrollIntoView({
                behavior: "smooth",
                block: "center"
            });
        },
        optionNav(adjustBy) {
            // adjustBy = -1 for up, +1 for down
            this.show_options = true;
            let oldVal = this.focused_option_index;
            let newVal;
            let maxVal = this.filtered_option_ids.length - 1;
            if (oldVal === null) {
                if (adjustBy === -1) newVal = maxVal;
                else newVal = 0;
            } else {
                newVal = oldVal + adjustBy;
                let minVal = (this.current_value ? -1 : 0);
                if (newVal > maxVal) {
                    newVal = minVal;
                } else if (newVal < minVal) {
                    newVal = maxVal;
                }
            }
            this.focused_option_index = newVal;
            this.scrollToFocus();
        },
        selectOption(index = null) {
            if (index !== null) {
                this.focused_option_index = index;
            } else if (this.focused_option_index === -1) {
                this.clearValue();
                return;
            }
            let optionID = _.get(this.filtered_option_ids, this.focused_option_index);
            if (optionID === null) {
                this.focused_option_index = null;
                this.show_options = false;
                return;
            }
            let selectedOption = _.get(this.options, optionID);
            if (!selectedOption) {
                this.show_options = false;
                return;
            }
            this.current_value = selectedOption.value;
            if (this.as_select) {
                this.current_label = selectedOption.label;
                this.current_input = selectedOption.label;
                this.show_input = false;
                this.filterOptions(selectedOption.label, false);
                this.$nextTick(() => { this.$refs.select_button.focus() });
            } else {
                this.current_input = selectedOption.value;
            }
            this.show_options = false;
        },
        onSelectButtonClick() {
            this.show_input = true;
            this.$nextTick(() => { this.$refs.input_el.focus() });
        },
        onLeaveInput(e) {
            if (this.$refs.input_group.contains(e.relatedTarget)) return;
            this.show_options = false;
            if (this.as_select) this.show_input = false;
        },
        clearInput() {
            this.current_input = '';
            this.filterOptions('', false);
            this.$refs.input_el.focus();
        },
        clearValue() {
            this.current_value = null;
            this.current_label = null;
            this.clearInput();
        },
        onClearInputTabAway() {
            if (this.as_select) {
                this.$nextTick(() => { this.$refs.select_button.focus() });
            }
        },
    }
}