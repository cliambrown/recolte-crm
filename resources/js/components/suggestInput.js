window.suggestInput = function (data) {
    return {
        current_input: data.current_input,
        show_options: false,
        options: [],
        options_filtered: [],
        focused_option_index: null,
        open: false,
        fuse: null,
        getOptions() {
            return JSON.parse(JSON.stringify(this.options));
        },
        init() {
            for (var i=0, len=data.options.length; i<len; ++i) {
                let option = data.options[i];
                this.options.push({
                    name: option,
                    simple_name: simpleSearchStr(option),
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
            this.filterOptions(this.current_input);
        },
        filterOptions(input) {
            input = simpleSearchStr(input);
            if (!input) {
                this.options_filtered = _.map(this.getOptions(), 'name');
            } else {
                let results = this.fuse.search(input);
                this.options_filtered = _.map(results, 'item.name');
            }
        },
        selectOption(index = null) {
            if (index !== null) {
                this.focused_option_index = index;
            }
            if (this.focused_option_index < 0 || this.focused_option_index >= this.options.length) {
                this.focused_option_index = null;
            } else {
                this.current_input = this.options[this.focused_option_index].name;
            }
            this.show_options = false;
        },
        optionDown() {
            this.show_options = true;
            if (this.focused_option_index === null) {
                this.focused_option_index = 0;
            } else if (this.focused_option_index + 1 >= this.options.length) {
                this.focused_option_index = this.options.length - 1;
            } else {
                ++this.focused_option_index;
            }
            this.scrollToFocus();
        },
        optionUp() {
            this.show_options = true;
            if (this.focused_option_index === null) {
                this.focused_option_index = this.options.length - 1;
            } else if (this.focused_option_index <= 0) {
                this.focused_option_index = 0;
            } else {
                --this.focused_option_index;
            }
            this.scrollToFocus();
        },
        scrollToFocus() {
            if (this.focused_option_index === null) return false;
            this.$refs.listbox.children[this.focused_option_index].scrollIntoView({
                behavior: "smooth",
                block: "center"
            });
        },
    }
}