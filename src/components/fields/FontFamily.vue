<template>
	<k-select-field
		class="k-fontfamily-field"
		:help="help"
		:label="label"
		:when="when"
		:default="this.default"
		:disabled="disabled"
		:value="value"
		:options="options"
		@input="onInput"
	/>
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
		reload: { type: Boolean, default: false },
		loading: { type: Boolean, default: false }
	},
	data() {
		return {
			options: [],
			loading: false
		};
	},
	created() {
		this.syncContent();
		this.$events.on("model.update", () => this.reload && this.syncContent());
	},
	methods: {
		onInput(value) {
			this.$emit("input", value);
		},
		syncContent() {
			this.loading = true;
			this.$api
				.get("fontselector/families?reload=" + (this.reload ? "1" : "0"))
				.then((response) => {
					this.options = response.families ? response.families : [];
					if (
						this.options.some((item) => item.value === this.value) === false
					) {
						this.onInput(""); // needs to be a string not null
					}
					this.loading = false;
				})
				.catch(() => {
					this.loading = false;
				});
		}
	}
};
</script>

<style></style>
