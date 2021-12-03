window.deleteForm = function (data) {
    return {
        confirm_msg: data.confirm_msg,
        onSubmit() {
            if (window.confirm(this.confirm_msg)) {
                this.$refs.form.submit();
            }
        },
    }
}