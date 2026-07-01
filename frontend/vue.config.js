const webpack = require("webpack");

module.exports = {
  lintOnSave: false,
  configureWebpack: {
    resolve: {
      fallback: {
        path: require.resolve("path-browserify"),
        assert: require.resolve("assert/"),
        buffer: require.resolve("buffer/"),
        process: require.resolve("process/browser")
      }
    },
    plugins: [
      new webpack.ProvidePlugin({
        process: "process/browser",
        Buffer: ["buffer", "Buffer"]
      })
    ]
  }
};