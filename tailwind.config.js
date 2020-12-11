module.exports = {
	purge: {
		mode: "all",
		preserveHtmlElements: false,
		content: ["./**/*.php"],
		options: { keyframes: true },
	},
	darkMode: false, // or 'media' or 'class'
	theme: {
		extend: {
			// boxShadow: {
			// 	"wildo-blue": "0 2px 10px 2px rgba(3, 169, 244, 0.25)",
			// },
		},
	},
	variants: {
		extend: {},
	},
	plugins: [],
	prefix: "wmp-",
};
