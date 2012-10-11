/**
 * Prefetches the content of next chapter page.
 *
 * This file is part of NOVEL.READER.
 *
 * NOVEL.READER is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * NOVEL.READER is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with NOVEL.READER.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package   novel.reader
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2012 snakevil.in
 * @license   http://www.gnu.org/licenses/gpl.html
 */

if (_ && _.$ && _.n && _.n.length && "#" != _.n[0]) {
	_.l = function (x) {
		var y = document;
		if ("FIELDSET" == y.body.lastChild.nodeName)
			y.body.removeChild(y.body.lastChild);
		y.body.appendChild(y.createElement("fieldset")).appendChild(y.createElement("img")).src = x;
	}
	if (history.pushState)
		(function () {
			_.c = function (x, y) {
				var i = x.split("/"), j = y.split("/");
				i.pop();
				for (var k = 0; k < j.length; k++)
					if (".." == j[k] && 3 < i.length)
						i.pop();
					else
						i.push(j[k]);
				return i.join("/");
			};
			_.d = function (x) {
				with (document.getElementsByTagName("blockquote")[0]) {
					innerHTML = x.b;
					setAttribute("cite", x.r);
				}
				_.r = x.r;
				document.title = x.t[0];
				_.$("tocLink").textContent = x.t[1];
				_.$("prevLink").href = _.p = x.p;
				_.$("nextLink").href = _.n = x.n;
			};
			_.g = function (x, y) {
				if (!x || !y) return;
				var i = _.c(location.href, x);
				if (_.h.has(i)) return;
				var j = new XMLHttpRequest;
				j.onload = function () {
					try {
						var k = JSON.parse(j.responseText);
						if (200 != k.code || _.c(y, x) != k.referer) {
							_.$("nextLink").href = "#" + x;
							return;
						}
						for (var l = [], m = "", n = 0; n < k.data.paragraphs.length; n++)
							if ("![IMAGE](" == k.data.paragraphs[n].substr(0, 9)) {
								l[l.length] = k.data.paragraphs[n].substring(9, k.data.paragraphs[n].length - 1);
								m += "<p><img src=\"" + l[l.length - 1] + "\"/></p>";
							} else
								m += "<p>" + k.data.paragraphs[n] + "</p>";
						_.h.push(i, {
							b : m,
							n : k.data.links.next,
							r : k.referer,
							t : [
								k.data.novelTitle + " - " + k.data.title + " * CCNR",
								k.data.title
							],
							p : k.data.links.previous
						});
						if (l.length)
							_.l(l[0]);
					} catch (ex) {}
				};
				j.open("GET", _.a + _.c(y, x), true);
				j.send();
			};
			_.h = new (function () {
				this.index = [location.href];
				this.data = {};
				this.data[location.href] = {
					b : document.getElementsByTagName("blockquote")[0].innerHTML,
					n : _.n,
					r : _.r,
					t : [document.title, _.$("tocLink").textContent],
					p : _.p
				};
				this.get = function (x) {
					if (this.has(x)) return this.data[x];
				};
				this.has = function (x) {
					return !!this.data[x];
				};
				this.push = function (x, y) {
					this.index[this.index.length] = x;
					this.data[x] = y;
					return this;
				}
			});
			_.$("nextLink").onclick = function (ev) {
				var x = this.href;
				if (!_.h.has(x)) return;
				var y = _.h.get(x);
				ev.preventDefault();
				if (this.onclick.locked) return;
				this.onclick.locked = true;
				history.pushState(y, y.t[0], x);
				_.d(y);
				window.scrollTo(0, 0);
				delete this.onclick.locked;
				_.g(y.n, _.r);
			};
			window.onpopstate = function (ev) {
				if (!this.onpopstate.loaded) {
					this.onpopstate.loaded = true;
					return;
				}
				_.d(!ev.state ? _.h.get(location.href) : ev.state);
			};
			history.replaceState(_.h.get(location.href), document.title, location.href);
			_.g(_.n, _.r);
		})();
	else
		_.l(_.n);
}

// vim: se ft=javascript ff=unix fenc=utf-8 tw=120 sw=2 ts=2 sts=2 noet:
