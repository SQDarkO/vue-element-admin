module.exports = {
    devServer: {
      port: 8080, // Vue项目启动的端口号
      host: 'localhost',
      https: false, // https:{type:Boolean}
      open: true, // 配置自动启动浏览器
      // proxy: 'http://localhost:8070' // 配置跨域处理,只有一个代理
      proxy: {
        '/sell': {
          target: 'http://phpapi.com', // 需要跨域请求的地址
          changeOrigin: true, // 是否跨域
          pathRewrite: {
            '^/sell': '/' // 替换target中的请求地址，也就是说，在请求的时候，url用'/proxy'代替'http://ip.taobao.com'
          }
        }
      } // 配置多个代理
    }
  }