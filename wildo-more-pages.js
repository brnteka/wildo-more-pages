"use strict";

function wildoMorePages() {
  return {
    open: false,
    height: 0,
    delta: null,
    speed: 1000,
    listScrollHeight: null,
    setScrollHeight: function setScrollHeight() {
      var _this = this;

      this.$nextTick(function () {
        var scrollHeight = _this.$refs.list.scrollHeight;
        _this.listScrollHeight = scrollHeight;
        _this.delta = scrollHeight / _this.speed * 60;
      });
    },
    slideDown: function slideDown() {
      var _this = this;

      function step() {
        if (_this.height < _this.listScrollHeight) {
          _this.height = Math.min(_this.height += _this.delta, _this.listScrollHeight);
          window.requestAnimationFrame(step);
        }
      }

      window.requestAnimationFrame(step);
    },
    slideUp: function slideUp() {
      var _this = this;

      function step() {
        if (_this.height > 0) {
          _this.height = Math.max(_this.height -= _this.delta, 0);
          window.requestAnimationFrame(step);
        }
      }

      window.requestAnimationFrame(step);
    },
    changeHeight: function changeHeight() {
      this.open = !this.open;

      if (this.open) {
        this.slideDown();
      } else {
        this.slideUp();
      }
    }
  };
}
