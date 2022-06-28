<template>
  <k-select-field class="k-fontfamily-field"
                  :help="help"
                  :label="label"
                  :when="when"
                  :default="this.default"
                  :disabled="disabled"
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
    default: String,
    disabled: Boolean,
    value: String,
    options: Array,
    loading: {type: Boolean, default: false},
  },
  methods: {
    onInput(value) {
      this.$emit("input", value);
    },
  }
};
</script>

<style>

</style>
