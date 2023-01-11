const path = require("path");
const crypto = require("crypto");
const argv = require("webpack-nano/argv");
const { WebpackManifestPlugin } = require("webpack-manifest-plugin");
const MiniCSSExtractPlugin = require("mini-css-extract-plugin");
const GoogleFontsPlugin = require("@beyonk/google-fonts-webpack-plugin");

const fonts = [
    {
        family: "Montserrat",
        variants: ["300", "300italic", "400", "400italic", "700", "700italic"],
    },
    {
        family: "Oswald",
        variants: ["400"],
    },
];

const fonthash = crypto
    .createHash("sha256")
    .update(JSON.stringify(fonts))
    .digest("hex")
    .slice(0, 6);

module.exports = {
    watch: argv.watch,
    mode: "production",
    entry: {
        main: "./library/js/index.js",
        frontend: "./library/css/main.scss",
        editor: "./library/css/editor.scss",
    },
    output: {
        clean: true,
        publicPath: "build", // https://stackoverflow.com/a/64715069
        filename: "[name].[contenthash].js",
        path: path.resolve(__dirname, "build"),
    },
    module: {
        rules: [
            {
                test: /\.s[ac]ss$/i,
                use: [MiniCSSExtractPlugin.loader, "css-loader", "sass-loader"],
            },
        ],
    },
    plugins: [
        new GoogleFontsPlugin({
            fonts,
            apiUrl: "https://gwfh.mranftl.com/api/fonts",
            filename: `fonts.${fonthash}.css`,
            formats: ["woff2"],
        }),
        new MiniCSSExtractPlugin({
            filename: "[name].[contenthash].css",
        }),
        new WebpackManifestPlugin({
            basePath: "build",
        }),
    ],
};
