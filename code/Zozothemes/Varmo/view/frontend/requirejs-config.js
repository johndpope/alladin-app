var config = {
    map: {
        '*': {
            fancybox_button: 'Zozothemes_Varmo/js/fancybox/jquery.fancybox-buttons',
            fancybox_media: 'Zozothemes_Varmo/js/fancybox/jquery.fancybox-media',
            fancybox_thumbs: 'Zozothemes_Varmo/js/fancybox/jquery.fancybox-thumbs',
            fancybox: 'Zozothemes_Varmo/js/fancybox/jquery.fancybox',
            fancybox_pack: 'Zozothemes_Varmo/js/fancybox/jquery.fancybox.pack',
            mousewheel: 'Zozothemes_Varmo/js/fancybox/jquery.mousewheel-3.0.6.pack',
            stickyhead: 'Zozothemes_Varmo/js/sticky/jquery.sticky',
            easingjs_other: 'Zozothemes_Varmo/js/others/jquery.easing.1.3'
        }
    },
    paths: {
        "jquery.bootstrap": "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min"
    },
    shim: {
        'fancybox':{
            'deps':['jquery']
        },
        'fancybox_pack':{
            'deps':['jquery']
        },
        'jquery.bootstrap': {
            'deps': ['jquery']
        },
        'easingjs_other': {
            'deps': ['jquery']
        }
    }
};

