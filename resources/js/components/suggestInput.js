window.suggestInput = function (data) {
    return {
        as_select: _.get(data, 'as_select', false),
        id: data.id,
        current_value: _.get(data, 'current_value'),
        current_input: data.current_input,
        show_input: !data.as_select,
        show_options: false,
        options: [],
        options_unfiltered: [],
        options_filtered: [],
        focused_option_index: null,
        open: false,
        fuse: null,
        getOptions() {
            return JSON.parse(JSON.stringify(this.options));
        },
        init() {
            for (var i=0, len=data.options.length; i<len; ++i) {
                let optionName = data.options[i];
                let option = {
                    name: optionName,
                    simple_name: simpleSearchStr(optionName),
                };
                this.options.push(option);
                // Get them into Fuse.js format
                this.options_unfiltered.push({
                    item: option,
                    refIndex: i,
                    score: 1,
                });
            }
            const fuseOptions = {
                keys: ['simple_name'],
                includeScore: true,
                isCaseSensitive: true,
                threshold: 0.35,
            };
            const myIndex = Fuse.createIndex(fuseOptions.keys, this.getOptions());
            this.fuse = new Fuse(this.getOptions(), fuseOptions, myIndex);
            this.filterOptions(this.current_input, false);
        },
        filterOptions(input, doShowOptions) {
            input = simpleSearchStr(input);
            if (!input) {
                this.options_filtered = JSON.parse(JSON.stringify(this.options_unfiltered));
            } else {
                this.options_filtered = this.fuse.search(input);
            }
            if (this.options_filtered.length) {
                this.focused_option_index = 0;
            } else {
                this.focused_option_index = null;
            }
            if (doShowOptions) {
                this.show_options = true;
                this.$nextTick(() => { this.scrollToFocus() });
            }
        },
        selectOption(index = null) {
            if (index !== null) {
                this.focused_option_index = index;
            }
            if (this.focused_option_index < 0 || this.focused_option_index >= this.options_filtered.length) {
                this.focused_option_index = null;
            } else {
                let selectedFilteredOption = this.options_filtered[this.focused_option_index];
                let val = selectedFilteredOption.item.name;
                this.current_input = val;
                if (this.as_select) {
                    this.current_value = val;
                    this.filterOptions(val, false);
                    this.show_input = false;
                    this.$nextTick(() => { this.$refs.select_button.focus() });
                }
            }
            this.show_options = false;
        },
        optionDown() {
            this.show_options = true;
            if (this.focused_option_index === null) {
                this.focused_option_index = 0;
            } else if (this.focused_option_index + 1 >= this.options_filtered.length) {
                this.focused_option_index = this.options_filtered.length - 1;
            } else {
                ++this.focused_option_index;
            }
            this.scrollToFocus();
        },
        optionUp() {
            this.show_options = true;
            if (this.focused_option_index === null) {
                this.focused_option_index = this.options_filtered.length - 1;
            } else if (this.focused_option_index <= 0) {
                this.focused_option_index = 0;
            } else {
                --this.focused_option_index;
            }
            this.scrollToFocus();
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
        onSelectButtonClick() {
            this.show_input = true;
            this.$nextTick(() => { this.$refs.input_el.focus() });
        },
        onLeaveInput(e) {
            if (this.$refs.input_group.contains(e.relatedTarget)) return;
            this.show_options = false;
            if (this.as_select) this.show_input = false;
        },
        onClear() {
            this.current_input = '';
            this.filterOptions('', false);
            this.$refs.input_el.focus();
        },
    }
}