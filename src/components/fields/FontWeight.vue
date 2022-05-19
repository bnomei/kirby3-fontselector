<template>
  <k-select-field class="k-fontweight-field"
                  :help="help"
                  :label="label"
                  :when="when"
                  :default="this.default"
                  :value="value"
                  :options="options" 
                  @input="onInput"/>
</template>

<script>
export default {
  props: {
    help: String,
    label: String,
    when: String,
    watchField: String,
    default: String,
    value: String,
    options: Array,
    loading: {type: Boolean, default: false},
  },
  data() {
    return {
      resetToFont: undefined,
      hasUserChange: false,
    }
  },
  created() {
    this.syncContent(
      this.$store.getters["content/values"]()[this.watchField]
    )
  },
  watch: {
    hasChanges() {
      // post save or on revert
      if (this.hasChanges && this.watchField in this.$store.getters["content/changes"]()) {
        this.syncContent(
          this.$store.getters["content/changes"]()[this.watchField]
        )
      } 

      if (!this.hasChanges) {
        this.syncContent(
          this.resetToFont
        )
      }
    }
  },
  computed: {
    hasChanges() {
      return this.$store.getters["content/hasChanges"]();
    },
  },
  methods: {
    onInput(value) {
      this.$emit("input", value);
    },
    syncContent(family) {
      this.$api.get('fontselector/family/' + encodeURIComponent(family))
          .then(response => {
            this.options = response.weight
            if (!this.resetToFont) {
              this.resetToFont = response.font
            }
            // this.value = this.value // force refresh?
            this.loading = false
          })
          .catch(error => {
            this.loading = false
          })
    }
  },
};
</script>

<style>

</style>
