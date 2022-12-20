
export default defineNuxtConfig({
    ssr: false,
    runtimeConfig: {
        public : {
            apiLocalBaseUrl : "http://localhost/vtupal/apis",
            apiProductionBaseUrl : "https://akfemtopup.com.ng/vtupal/apis",
        }
    },
    nitro : {
      preset : "node-server"
    },
    css: [
        '@/assets/css/main.css',
    ],
    postcss: {
      postcssOptions: require('./postcss.config.js'),
      plugins: {
        tailwindcss: {},
        autoprefixer: {},
      },
    },
    modules: [
      [
        '@pinia/nuxt',
        '@nuxt/postcss8',
        {
          autoImports: [
            'defineStore',
            ['defineStore', 'definePiniaStore'],
          ],
        },
      ]
    ],
    
    imports: {
      dirs: [
        "composables",
        'composables/**',
        "stores/**"
      ]
    }

})
