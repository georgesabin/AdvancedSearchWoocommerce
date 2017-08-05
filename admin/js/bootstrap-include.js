/**
* Load all css and js files
**/

var bootstrapCss = 'bootstrapCss';

if (!document.getElementById(bootstrapCss))
{
    var head = document.getElementsByTagName('head')[0];
    var bootstrapWrapper = document.createElement('link');
    bootstrapWrapper.id = bootstrapCss;
    bootstrapWrapper.rel = 'stylesheet/less';
    bootstrapWrapper.type = 'text/css';
    bootstrapWrapper.href = '../wp-content/plugins/AdvancedSearchWoocommerce/admin/css/bootstrap-wrapper.less';
    bootstrapWrapper.media = 'all';
    head.appendChild(bootstrapWrapper);
    console.log(bootstrapWrapper);

    var lessjs = document.createElement('script');
    lessjs.type = 'text/javascript';
    lessjs.src = '../wp-content/plugins/AdvancedSearchWoocommerce/general/js/less.min.js';
    head.appendChild(lessjs);

    //load other stylesheets that override bootstrap styles here, using the same technique from above

    var aswStyle = document.createElement('link');
    aswStyle.id = 'asw-style';
    aswStyle.rel = 'stylesheet';
    aswStyle.type = 'text/css';
    aswStyle.href = '../wp-content/plugins/AdvancedSearchWoocommerce/admin/css/style.css';
    aswStyle.media = 'all';
    head.appendChild(aswStyle);
}
