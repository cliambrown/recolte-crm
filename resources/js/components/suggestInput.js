window.suggestInput = function (data) {
    return {
        as_select: data.as_select,
        allow_multiple: (data.as_select && data.allow_multiple),
        id: data.id,
        options: {},
        option_ids: [],
        filtered_option_ids: [],
        selected_option_ids: [],
        focused_option_index: null,
        current_input: data.current_input, // What goes into the text input
        current_value: data.current_value, // What is displayed on the "select" overlay (if needed)
        show_input: !data.as_select,
        show_options: false,
        open: false,
        fuse: null,
        init() {
            let optionsCount = data.options.length;
            let optionsArr = []; // just for Fuse!
            for (let i=0; i<optionsCount; ++i) {
                let option = data.options[i];
                let optionName, optionId;
                if (typeof option === 'string') {
                    optionName = option;
                    optionId = i;
                } else if (typeof option === 'object') {
                    optionName = _.get(option, 'name', '');
                    optionId = _.get(option, 'id', i);
                } else {
                    continue;
                }
                let optionObj = {
                    id: optionId,
                    name: optionName,
                    simple_name: simpleSearchStr(optionName),
                };
                this.options[optionId] = optionObj;
                optionsArr.push(optionObj);
                this.option_ids.push(optionId);
                this.filtered_option_ids.push(optionId);
            }
            const fuseOptions = {
                keys: ['simple_name'],
                includeScore: true,
                isCaseSensitive: true,
                threshold: 0.35,
            };
            const myIndex = Fuse.createIndex(fuseOptions.keys, optionsArr);
            this.fuse = new Fuse(optionsArr, fuseOptions, myIndex);
            if (this.current_input) {
                this.filterOptions(this.current_input, false);
            }
        },
        filterOptions(input, doShowOptions) {
            input = simpleSearchStr(input);
            if (!input) {
                this.filtered_option_ids = getAlpineObj(this.option_ids);
            } else {
                let result = this.fuse.search(input);
                this.filtered_option_ids = _.map(result, 'item.id');
            }
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
        optionDown() {
            this.show_options = true;
            if (this.focused_option_index === null) {
                this.focused_option_index = 0;
            } else if (this.focused_option_index + 1 >= this.filtered_option_ids.length) {
                this.focused_option_index = this.filtered_option_ids.length - 1;
            } else {
                ++this.focused_option_index;
            }
            this.scrollToFocus();
        },
        optionUp() {
            this.show_options = true;
            if (this.focused_option_index === null) {
                this.focused_option_index = this.filtered_option_ids.length - 1;
            } else if (this.focused_option_index <= 0) {
                this.focused_option_index = 0;
            } else {
                --this.focused_option_index;
            }
            this.scrollToFocus();
        },
        selectOption(optionID = null, index = null) {
            if (index !== null) {
                this.focused_option_index = index;
            }
            if (optionID === null) {
                optionID = _.get(this.filtered_option_ids, this.focused_option_index);
                if (optionID === null) {
                    this.focused_option_index = null;
                    this.show_options = false;
                    return;
                }
            }
            let selectedOption = _.get(this.options, optionID);
            if (!selectedOption) {
                this.show_options = false;
                return;
            }
            let optionName = selectedOption.name;
            if (this.as_select) {
                this.current_value = optionName;
                this.current_input = optionName;
                this.show_input = false;
                this.filterOptions(optionName, false);
                this.$nextTick(() => { this.$refs.select_button.focus() });
            } else {
                this.current_input = optionName;
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
        onClear() {
            this.current_input = '';
            this.filterOptions('', false);
            this.$refs.input_el.focus();
        },
        
        

        
    
        
        
        
        
        
        
        // current_value: data.current_value,
        // current_input: data.current_input,
        // filtered_option_indices: [],
        // focused_dropdown_index: null,
        // selected_option_index: null,
        // selected_option_indices: [],
        // show_input: !data.as_select,
        // show_options: false,
        // open: false,
        // fuse: null,
        // init() {
        //     for (var i=0, len=data.options.length; i<len; ++i) {
        //         let option = data.options[i];
        //         let optionName, optionId;
        //         if (typeof option === 'string') {
        //             optionName = option;
        //             optionId = i;
        //         } else if (typeof option === 'object') {
        //             optionName = _.get(option, 'name', '');
        //             optionId = _.get(option, 'id', i);
        //         }
        //         let optionObj = {
        //             id: optionId,
        //             refIndex: i,
        //             name: optionName,
        //             simple_name: simpleSearchStr(optionName),
        //         };
        //         this.options.push(optionObj);
        //     }
        //     const fuseOptions = {
        //         keys: ['simple_name'],
        //         includeScore: true,
        //         isCaseSensitive: true,
        //         threshold: 0.35,
        //     };
        //     const myIndex = Fuse.createIndex(fuseOptions.keys, this.options);
        //     this.fuse = new Fuse(this.options, fuseOptions, myIndex);
        //     this.filterOptions(this.current_input, false);
        // },
        // filterOptions(input, doShowOptions) {
        //     input = simpleSearchStr(input);
        //     if (!input) {
        //         this.filtered_option_indices = Object.keys(this.options);
        //     } else {
        //         let result = this.fuse.search(input);
        //         this.filtered_option_indices = _.map(result, 'refIndex');
        //     }
        //     if (this.filtered_option_indices.length) {
        //         this.focused_dropdown_index = 0;
        //     } else {
        //         this.focused_dropdown_index = null;
        //     }
        //     if (doShowOptions) {
        //         this.show_options = true;
        //         this.$nextTick(() => { this.scrollToFocus() });
        //     }
        // },
        // selectOption(optIndex = null, dropdownIndex = null) {
        //     if (dropdownIndex !== null) {
        //         this.focused_dropdown_index = dropdownIndex;
        //     }
        //     if (this.focused_dropdown_index < 0 || this.focused_dropdown_index >= this.filtered_option_indices.length) {
        //         this.focused_dropdown_index = null;
        //         this.show_options = false;
        //         return;
        //     }
        //     let selectedOption = _.get(this.options, optIndex);
        //     if (!selectedOption) {
        //         this.show_options = false;
        //         return;
        //     }
        //     if (this.allow_multiple) {
        //         this.selected_option_indices.push(optIndex);
        //         this.current_value = null;
        //         this.current_input = '';
        //         this.filterOptions('', false);
        //         this.$refs.input_el.focus();
        //     } else if (this.as_select) {
        //         let optionName = selectedOption.name;
        //         this.current_value = optionName;
        //         this.current_value = optionName;
        //     }
            
            
        //     //     // let selectedOption = this.filtered_option_indices[this.focused_dropdown_index];
        //     //     let selectedOption = _.get(this.options, optIndex);
        //     //     if (!this.selectOption) return;
                
        //     //     let optionName = _.get(this.options, optIndex+'.name');
        //     //     if (optionName === null) return;
                
                
        //     //     let val = selectedOption.item.name;
        //     //     this.current_input = val;
        //     //     if (this.allow_multiple) {
        //     //         this.values.push(val);
        //     //         this.current_value = null;
        //     //         this.current_input = '';
        //     //         this.filterOptions('', false);
        //     //         this.$refs.input_el.focus();
        //     //     } else if (this.as_select) {
        //     //         this.current_value = val;
        //     //         this.filterOptions(val, false);
        //     //         this.show_input = false;
        //     //         this.$nextTick(() => { this.$refs.select_button.focus() });
        //     //     }
        //     // }
        //     // this.show_options = false;
        // },
        // optionDown() {
        //     this.show_options = true;
        //     if (this.focused_dropdown_index === null) {
        //         this.focused_dropdown_index = 0;
        //     } else if (this.focused_dropdown_index + 1 >= this.filtered_option_indices.length) {
        //         this.focused_dropdown_index = this.filtered_option_indices.length - 1;
        //     } else {
        //         ++this.focused_dropdown_index;
        //     }
        //     this.scrollToFocus();
        // },
        // optionUp() {
        //     this.show_options = true;
        //     if (this.focused_dropdown_index === null) {
        //         this.focused_dropdown_index = this.filtered_option_indices.length - 1;
        //     } else if (this.focused_dropdown_index <= 0) {
        //         this.focused_dropdown_index = 0;
        //     } else {
        //         --this.focused_dropdown_index;
        //     }
        //     this.scrollToFocus();
        // },
        // scrollToFocus() {
        //     if (this.focused_dropdown_index === null) return;
        //     let el = document.getElementById(this.id + '-option-' + this.focused_dropdown_index);
        //     if (!el) return;
        //     el.scrollIntoView({
        //         behavior: "smooth",
        //         block: "center"
        //     });
        // },
        // onSelectButtonClick() {
        //     this.show_input = true;
        //     this.$nextTick(() => { this.$refs.input_el.focus() });
        // },
        // onLeaveInput(e) {
        //     if (this.$refs.input_group.contains(e.relatedTarget)) return;
        //     this.show_options = false;
        //     if (this.as_select) this.show_input = false;
        // },
        // onClear() {
        //     this.current_input = '';
        //     this.filterOptions('', false);
        //     this.$refs.input_el.focus();
        // },
        // removeValue(valIndex) {
        //     if (!_.isInteger(valIndex)) return;
        //     if (valIndex < 0 || valIndex > this.values.length - 1) return;
        //     this.values.splice(valIndex, 1);
        // },
    }
}