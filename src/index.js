import FontFamilyField from "./components/fields/FontFamily.vue";
import FontWeightField from "./components/fields/FontWeight.vue";

panel.plugin("bnomei/fontselector", {
  fields: {
  fontfamily: FontFamilyField,
  fontweight: FontWeightField,
  },
});
