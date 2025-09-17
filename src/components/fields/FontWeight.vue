<template>
	<k-select-field
		v-model="value"
		class="k-fontweight-field"
		:help="help"
		:label="label"
		:when="when"
		:default="this.default"
		:disabled="disabled"
		:options="options"
		:required="required"
		@input="onInput"
	/>
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
		required: { type: Boolean, default: true }
	},
	data() {
		return {
			options: [],
			loading: false
		};
	},
	computed: {
		hasChanges() {
			return Object.keys(this.$panel.content.diff()).length > 0;
		},
		fontFamily() {
			return this.$panel.view.props.versions.changes[this.watchField];
		}
	},
	watch: {
		fontFamily() {
			this.syncContent(this.fontFamily);
		}
	},
	created() {
		this.syncContent(this.$panel.view.props.versions.changes[this.watchField]);
	},
	methods: {
		onInput(value) {
			this.$emit("input", value);
		},
		syncContent(family) {
			if (family === undefined || family.length === 0) {
				this.options = [];
				this.onInput(""); // needs to be a string not null
				return;
			}
			this.loading = true;
			this.$api
				.get("fontselector/family/" + encodeURIComponent(family))
				.then((response) => {
					this.options = response.weight ? response.weight : [];
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
