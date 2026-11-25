<template>
	<k-select-field
		class="k-fontweight-field"
		:value="value"
		:options="options"
		@input="$emit('input', $event)"
	/>
</template>

<script>
export default {
	props: {
		value: String,
		watchField: String
	},
	emits: ["input"],
	data() {
		return { options: [] };
	},
	computed: {
		fontFamily() {
			return this.$panel.view.props.versions.changes[this.watchField];
		}
	},
	watch: {
		fontFamily: {
			immediate: true,
			async handler(family) {
				const options = family
					? (
							await this.$api.get(
								"fontselector/family/" + encodeURIComponent(family)
							)
					  ).weight
					: [];
				if (family !== this.fontFamily) return;
				this.options = options;
				if (this.value && !options.some((item) => item.value === this.value)) {
					this.$emit("input", "");
				}
			}
		}
	}
};
</script>
