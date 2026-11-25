<template>
	<k-select-field
		class="k-fontfamily-field"
		:value="value"
		:options="options"
		@input="$emit('input', $event)"
	/>
</template>

<script>
export default {
	props: {
		value: String,
		reload: Boolean
	},
	emits: ["input"],
	data() {
		return { options: [] };
	},
	created() {
		this.syncContent();
		if (this.reload) this.$panel.events.on("model.update", this.syncContent);
	},
	beforeUnmount() {
		if (this.reload) this.$panel.events.off("model.update", this.syncContent);
	},
	methods: {
		async syncContent() {
			const response = await this.$api.get(
				"fontselector/families?reload=" + (this.reload ? "1" : "0")
			);
			this.options = response.families;
			if (
				this.value &&
				!this.options.some((item) => item.value === this.value)
			) {
				this.$emit("input", "");
			}
		}
	}
};
</script>
