window.expandableNotes = function () {
    return {
        expand: false,
        hasOverflowed: false,
        init() {
            this.$nextTick(() => {
                this.updateHasOverflowed();
            });
            window.addEventListener('resize', e => {
                window.setTimeout(() => {
                    this.updateHasOverflowed();
                }, 10);
            });
        },
        updateHasOverflowed() {
            this.hasOverflowed = hasOverflowed(this.$refs.notes);
        },
    }
}