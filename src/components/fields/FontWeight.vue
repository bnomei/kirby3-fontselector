<template>
  <k-select-field class="k-fontweight-field"
                  :help="help"
                  :label="label"
                  :when="when"
                  :default="this.default"
                  :disabled="disabled"
                  :options="options"
                  :required="required"
                 v-model="value"
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
    disabled: Boolean,
    value: String,
    required: { type: Boolean, default: true },
  },
  data() {
    return {
      options: [],
      loading: false,
    }
  },
  created() {
    this.syncContent(
      this.$store.getters["content/values"]()[this.watchField]
    )
  },
  watch: {
    fontFamily() {
      this.syncContent(
          this.fontFamily
      )
    }
  },
  computed: {
    hasChanges() {
      return this.$store.getters["content/hasChanges"]()
    },
    fontFamily() {
      return this.$store.getters["content/values"]()[this.watchField]
    },
  },
  methods: {
    onInput(value) {
      this.$emit("input", value);
    },
    syncContent(family) {
      if (family === undefined || family.length === 0) {
        this.options = []
        this.onInput('') // needs to be a string not null
        return
      }
      this.$api.get('fontselector/family/' + encodeURIComponent(family))
          .then(response => {
            this.options = response.weight ? response.weight : []
            if (this.options.some((item) => item.value === this.value) === false) {
              this.onInput('') // needs to be a string not null
            }
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
