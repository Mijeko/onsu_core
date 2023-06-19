BX.ready(function () {
    var MyPanel = BX("top-line-backend"),
        BxPanel = BX.admin.panel,
        FxPanel = function () {
            if (window.pageYOffset >= BxPanel.DIV.clientHeight && BxPanel.isFixed() === false) {
                MyPanel.style.top = 0;
            } else if (BxPanel.isFixed() === true) {
                MyPanel.style.top = BxPanel.DIV.clientHeight + "px";
            } else {
                MyPanel.style.top = BxPanel.DIV.clientHeight - window.pageYOffset + "px";
            }
        };
    if (!!MyPanel) {
        FxPanel();
        window.onscroll = FxPanel;
        BX.addCustomEvent('onTopPanelCollapse', BX.delegate(FxPanel, this));
        BX.addCustomEvent('onTopPanelFix', BX.delegate(FxPanel, this));
    }
});