// ==========================================
// ROSÉA BOUTIQUE - JAVASCRIPT
// ==========================================

document.addEventListener("DOMContentLoaded", function () {

	// ==========================================
	// CARRITO DE COMPRAS
	// ==========================================

	let carrito = JSON.parse(localStorage.getItem("carritoRosea")) || [];

	function guardarCarrito() {
		localStorage.setItem("carritoRosea", JSON.stringify(carrito));
	}

	const botonesCarrito = document.querySelectorAll(".agregar-carrito");

	// ==========================================
	// BUSCADOR Y FILTROS (coleccion.html)
	// ==========================================

	const productosContainer = document.querySelector('.productos');
	const buscarInput = document.getElementById('buscar');
	const filtroCategoria = document.getElementById('filtro-categoria');
	const ordenPrecio = document.getElementById('orden-precio');
	const filtroDisponible = document.getElementById('filtro-disponible');

	function applyFilters() {

		if (!productosContainer) return;

		const productos = Array.from(productosContainer.querySelectorAll('.producto'));

		const q = (buscarInput && buscarInput.value || '').toLowerCase().trim();
		const cat = filtroCategoria ? filtroCategoria.value : '';
		const orden = ordenPrecio ? ordenPrecio.value : '';
		const soloDisp = filtroDisponible ? filtroDisponible.checked : false;

		let listado = productos.map(function (el) {
			return {
				el: el,
				nombre: (el.querySelector('h3') ? el.querySelector('h3').textContent.toLowerCase() : ''),
				precio: Number(el.dataset.precio || 0),
				categoria: el.dataset.categoria || '',
				disponible: (el.dataset.disponible === 'true')
			};
		});

		// aplicar filtros
		listado = listado.filter(function (p) {
			if (q && p.nombre.indexOf(q) === -1) return false;
			if (cat && p.categoria !== cat) return false;
			if (soloDisp && !p.disponible) return false;
			return true;
		});

		// ordenar
		if (orden === 'asc') {
			listado.sort(function (a, b) { return a.precio - b.precio; });
		} else if (orden === 'desc') {
			listado.sort(function (a, b) { return b.precio - a.precio; });
		}

		// mostrar/ocultar sin destruir el DOM (evita que las imágenes desaparezcan)
		// primero ocultar todos
		productos.forEach(function(el){ el.style.display = 'none'; });

		// luego mostrar los filtrados en el orden calculado
		listado.forEach(function (p) {
			p.el.style.display = '';
		});

		// si se pidió orden, reinsertamos en el orden solicitado
		if (orden === 'asc' || orden === 'desc') {
			listado.forEach(function(p) { productosContainer.appendChild(p.el); });
		}

	}

	if (buscarInput) buscarInput.addEventListener('input', applyFilters);
	if (filtroCategoria) filtroCategoria.addEventListener('change', applyFilters);
	if (ordenPrecio) ordenPrecio.addEventListener('change', applyFilters);
	if (filtroDisponible) filtroDisponible.addEventListener('change', applyFilters);


	const contadorCarrito = document.getElementById("contador-carrito");
	const contadorCarritoHeader = document.getElementById("contador-carrito-header");
	const botonAbrirCarrito = document.getElementById("boton-carrito");
	const modalCarrito = document.getElementById("modal-carrito");
	const carritoItems = document.getElementById("carrito-items");
	const totalCarritoEl = document.getElementById("total-carrito");
	const cerrarModal = document.getElementById("cerrar-modal");
	const vaciarCarritoBtn = document.getElementById("vaciar-carrito");

	function actualizarContador() {
		const n = carrito.length;
		if (contadorCarrito) contadorCarrito.textContent = n;
		if (contadorCarritoHeader) contadorCarritoHeader.textContent = `(${n})`;
	}

	function renderCarrito() {
		if (!carritoItems || !totalCarritoEl) return;

		carritoItems.innerHTML = "";

		let total = 0;

		carrito.forEach(function (item, idx) {
			const fila = document.createElement("div");
			fila.className = "item-carrito";

			const nombre = document.createElement("div");
			nombre.textContent = item.nombre;

			const precio = document.createElement("div");
			precio.textContent = `RD$ ${item.precio.toLocaleString()}`;

			fila.appendChild(nombre);
			fila.appendChild(precio);

			carritoItems.appendChild(fila);

			total += item.precio;
		});

		totalCarritoEl.textContent = `RD$ ${total.toLocaleString()}`;
	}

	function actualizarCarrito() {
		actualizarContador();
		renderCarrito();
	}

	function abrirModal() {
		if (!modalCarrito) return;
		modalCarrito.setAttribute("aria-hidden", "false");
		renderCarrito();
	}

	function cerrarModalFn() {
		if (!modalCarrito) return;
		modalCarrito.setAttribute("aria-hidden", "true");
	}

	function vaciarCarrito() {
		carrito = [];
		guardarCarrito();
		actualizarContador();
		renderCarrito();
	}

	botonesCarrito.forEach(function (boton) {

		boton.addEventListener("click", function () {

			const nombre = boton.dataset.producto;
			const precio = Number(boton.dataset.precio);

			carrito.push({
				nombre: nombre,
				precio: precio
			});

			guardarCarrito();

			alert(
				"♡ " + nombre +
				" ha sido agregado al carrito."
			);

			console.log("Carrito:", carrito);

			actualizarContador();

		});

	});

	if (botonAbrirCarrito) {
		botonAbrirCarrito.addEventListener("click", function () {
			abrirModal();
		});
	}

	if (cerrarModal) {
		cerrarModal.addEventListener("click", function () {
			cerrarModalFn();
		});
	}

	if (vaciarCarritoBtn) {
		vaciarCarritoBtn.addEventListener("click", function () {
			vaciarCarrito();
		});
	}

	// Cerrar modal al hacer clic fuera del contenido
	if (modalCarrito) {
		modalCarrito.addEventListener("click", function (e) {
			if (e.target === modalCarrito) {
				cerrarModalFn();
			}
		});
	}

	// ==========================================
	// FORMULARIO DE CONTACTO
	// ==========================================

	const formulario = document.getElementById("formulario-contacto");

	if (formulario) {

		formulario.addEventListener("submit", function (evento) {

			evento.preventDefault();

			const nombre = document.getElementById("nombre").value;
			const correo = document.getElementById("correo").value;
			const mensaje = document.getElementById("mensaje").value;

			if (nombre === "" || correo === "" || mensaje === "") {

				alert(
					"♡ Por favor completa todos los campos necesarios."
				);

				return;
			}

			alert(
				"♡ Gracias, " + nombre +
				". Tu consulta ha sido enviada correctamente. " +
				"Roséa Boutique se pondrá en contacto contigo."
			);

			formulario.reset();

		});

	}
	// Inicializar carrito desde localStorage en la interfaz
	actualizarCarrito();

	// ==========================================
	// CHECKOUT: renderizado, cantidades, validación
	// ==========================================

	const checkoutApp = document.getElementById('checkout-app');
	if (checkoutApp) {
		const resumenItems = document.getElementById('resumen-items');
		const subtotalEl = document.getElementById('subtotal');
		const envioSelect = document.getElementById('envio');
		const totalEl = document.getElementById('total');
		const formCheckout = document.getElementById('form-checkout');

		function populateCheckoutUserData() {
			const session = getSession();
			if (!session || !session.email) return;
			const user = getCurrentUser();
			if (!user) return;
			const nombreCli = document.getElementById('nombre-cli');
			if (nombreCli && user.nombre) {
				nombreCli.value = user.nombre;
			}

			const formCheckoutTitle = document.querySelector('#checkout-app h2');
			if (formCheckoutTitle) {
				formCheckoutTitle.textContent = '🛍️ Finalizar compra como ' + user.nombre;
			}
		}

		function aggregateCart() {
			const map = {};
			carrito.forEach(function (item) {
				if (map[item.nombre]) {
					map[item.nombre].cantidad++;
				} else {
					map[item.nombre] = {
						nombre: item.nombre,
						precio: item.precio,
						cantidad: 1
					};
				}
			});
			return Object.values(map);
		}

		function renderCheckout() {
			const items = aggregateCart();
			resumenItems.innerHTML = '';
			let subtotal = 0;

			items.forEach(function (prod) {
				const row = document.createElement('div');
				row.className = 'item-row';

				const nombreDiv = document.createElement('div');
				nombreDiv.textContent = prod.nombre;

				const controles = document.createElement('div');
				controles.className = 'controles';

				const btnMinus = document.createElement('button');
				btnMinus.className = 'boton btn-cantidad';
				btnMinus.textContent = '−';
				btnMinus.dataset.nombre = prod.nombre;
				btnMinus.dataset.delta = -1;

				const spanCant = document.createElement('span');
				spanCant.textContent = prod.cantidad;

				const btnPlus = document.createElement('button');
				btnPlus.className = 'boton btn-cantidad';
				btnPlus.textContent = '+';
				btnPlus.dataset.nombre = prod.nombre;
				btnPlus.dataset.delta = 1;

				const btnEliminar = document.createElement('button');
				btnEliminar.className = 'boton btn-eliminar';
				btnEliminar.textContent = '🗑️';
				btnEliminar.dataset.nombre = prod.nombre;

				const precioDiv = document.createElement('div');
				precioDiv.style.minWidth = '90px';
				precioDiv.style.textAlign = 'right';
				precioDiv.textContent = `RD$ ${(prod.precio * prod.cantidad).toLocaleString()}`;

				controles.appendChild(btnMinus);
				controles.appendChild(spanCant);
				controles.appendChild(btnPlus);
				controles.appendChild(btnEliminar);
				controles.appendChild(precioDiv);

				row.appendChild(nombreDiv);
				row.appendChild(controles);

				resumenItems.appendChild(row);

				subtotal += prod.precio * prod.cantidad;
			});

			subtotalEl.textContent = `RD$ ${subtotal.toLocaleString()}`;
			const envioVal = Number(envioSelect ? envioSelect.value : 0);
			totalEl.textContent = `RD$ ${(subtotal + envioVal).toLocaleString()}`;

			// listeners
			resumenItems.querySelectorAll('.btn-cantidad').forEach(function (btn) {
				btn.addEventListener('click', function () {
					const nombre = this.dataset.nombre;
					const delta = Number(this.dataset.delta);
					changeQuantity(nombre, delta);
					renderCheckout();
				});
			});

			resumenItems.querySelectorAll('.btn-eliminar').forEach(function (btn) {
				btn.addEventListener('click', function () {
					const nombre = this.dataset.nombre;
					removeItem(nombre);
					renderCheckout();
				});
			});
		}

		function changeQuantity(nombre, delta) {
			if (delta > 0) {
				const found = carrito.find(function (i) { return i.nombre === nombre; });
				if (found) carrito.push({ nombre: found.nombre, precio: found.precio });
			} else {
				const idx = carrito.findIndex(function (i) { return i.nombre === nombre; });
				if (idx > -1) carrito.splice(idx, 1);
			}
			guardarCarrito();
			actualizarContador();
		}

		function removeItem(nombre) {
			carrito = carrito.filter(function (i) { return i.nombre !== nombre; });
			guardarCarrito();
			actualizarContador();
		}

		if (envioSelect) envioSelect.addEventListener('change', renderCheckout);

		if (formCheckout) {
			formCheckout.addEventListener('submit', function (e) {
				e.preventDefault();

				// campos
				const nombre = document.getElementById('nombre-cli').value.trim();
				const apellido = document.getElementById('apellido-cli').value.trim();
				const telefono = document.getElementById('telefono-cli').value.trim();
				const direccion = document.getElementById('direccion-cli').value.trim();
				const ciudad = document.getElementById('ciudad-cli').value.trim();
				const pago = document.getElementById('pago-cli').value;

				// validaciones simples
				const phoneOk = /^\+?[0-9\s\-]{7,15}$/.test(telefono);

				function setError(id, msg) {
					const el = document.getElementById(id);
					if (el) { el.textContent = msg; el.setAttribute('aria-hidden', 'false'); }
				}

				function clearError(id) {
					const el = document.getElementById(id);
					if (el) { el.textContent = ''; el.setAttribute('aria-hidden', 'true'); }
				}

				clearError('error-nombre'); clearError('error-apellido'); clearError('error-telefono'); clearError('error-direccion'); clearError('error-ciudad'); clearError('error-pago');

				let ok = true;
				if (!nombre) { setError('error-nombre', 'Requerido'); ok = false; }
				if (!apellido) { setError('error-apellido', 'Requerido'); ok = false; }
				if (!telefono || !phoneOk) { setError('error-telefono', 'Teléfono inválido'); ok = false; }
				if (!direccion) { setError('error-direccion', 'Requerido'); ok = false; }
				if (!ciudad) { setError('error-ciudad', 'Requerido'); ok = false; }
				if (!pago) { setError('error-pago', 'Selecciona método'); ok = false; }

				if (!ok) return;

				// preparar resumen y limpiar carrito
				const items = aggregateCart();
				let subtotal = 0;
				items.forEach(function (it) { subtotal += it.precio * it.cantidad; });
				const envioVal = Number(envioSelect ? envioSelect.value : 0);
				const total = subtotal + envioVal;

				const orderNum = 'R' + Date.now().toString().slice(-8) + Math.floor(Math.random() * 900 + 100);
				const session = getSession();
				const order = {
					id: orderNum,
					email: session && session.email ? session.email : null,
					nombreCliente: (nombre + ' ' + apellido).trim(),
					fecha: new Date().toISOString(),
					subtotal: subtotal,
					envio: envioVal,
					total: total,
					metodoPago: pago,
					estado: 'Confirmado',
					items: items.map(function (it) {
						return {
							nombre: it.nombre,
							precio: it.precio,
							cantidad: it.cantidad
						};
					})
				};

				if (session && session.email) {
					const orders = loadOrders();
					orders.push(order);
					saveOrders(orders);
				}

				// limpiar
				carrito = [];
				guardarCarrito();
				actualizarContador();

				checkoutApp.innerHTML = `<div class="confirmacion">
					<h3>Gracias por tu compra</h3>
					<p>Número de pedido: <strong>${orderNum}</strong></p>
					<p>Total: <strong>RD$ ${total.toLocaleString()}</strong></p>
					<h4>Resumen</h4>
					<div>${items.map(function(it){ return `<div>${it.cantidad}× ${it.nombre} — RD$ ${(it.precio*it.cantidad).toLocaleString()}</div>`; }).join('')}</div>
					<p>Hemos enviado la confirmación al correo proporcionado (simulado).</p>
					<a class="boton" href="index.html">Volver al inicio</a>
				</div>`;
			});
		}

		// Inicial render
		populateCheckoutUserData();
		renderCheckout();
	}

	// ==========================================
	// AUTENTICACIÓN (registro / login) - localStorage
	// ==========================================

	const ADMIN_EMAIL = 'admin@rosea.com';
	const ADMIN_PASSWORD = 'admin1234';

	function normalizeEmail(value) {
		return String(value || '').trim().toLowerCase();
	}

	function normalizeUser(user) {
		if (!user || typeof user !== 'object') return null;
		const email = normalizeEmail(user.email);
		if (!email) return null;
		return {
			nombre: String(user.nombre || email.split('@')[0] || 'Usuario').trim(),
			email: email,
			password: String(user.password || ''),
			role: user.role === 'admin' ? 'admin' : 'user',
			deleted: Boolean(user.deleted),
			deletedAt: user.deletedAt || null
		};
	}

	function normalizeUsers(users) {
		if (!Array.isArray(users)) return [];
		return users.map(normalizeUser).filter(Boolean);
	}

	function loadUsers() {
		const storedUsers = JSON.parse(localStorage.getItem('roseaUsers') || '[]');
		const users = normalizeUsers(storedUsers);
		const hasAdmin = users.some(function (u) { return u.email === ADMIN_EMAIL; });
		if (!hasAdmin) {
			users.push({
				nombre: 'Administrador',
				email: ADMIN_EMAIL,
				password: ADMIN_PASSWORD,
				role: 'admin',
				deleted: false,
				deletedAt: null
			});
		}
		saveUsers(users);
		return users;
	}

	function saveUsers(users) {
		localStorage.setItem('roseaUsers', JSON.stringify(normalizeUsers(users)));
	}

	function setSession(email) {
		localStorage.setItem('roseaSession', JSON.stringify({ email: normalizeEmail(email) }));
	}

	function clearSession() {
		localStorage.removeItem('roseaSession');
	}

	function getSession() {
		return JSON.parse(localStorage.getItem('roseaSession') || 'null');
	}

	function loadOrders() {
		return JSON.parse(localStorage.getItem('roseaOrders') || '[]');
	}

	function saveOrders(orders) {
		localStorage.setItem('roseaOrders', JSON.stringify(orders));
	}

	function getCurrentUser() {
		const session = getSession();
		if (!session || !session.email) return null;
		const users = loadUsers();
		const currentUser = users.find(function (u) { return u.email === normalizeEmail(session.email); });
		if (!currentUser || currentUser.deleted) {
			clearSession();
			return null;
		}
		return currentUser;
	}

	function getUserOrders(email) {
		if (!email) return [];
		return loadOrders().filter(function (order) { return order.email === normalizeEmail(email); });
	}

	function getActiveUsers() {
		return loadUsers().filter(function (user) { return !user.deleted; });
	}

	function getDeletedUsers() {
		return loadUsers().filter(function (user) { return user.deleted; });
	}

	function updateUserUI() {
		const userArea = document.getElementById('user-area');
		if (!userArea) return;
		const session = getSession();
		if (session && session.email) {
			const users = loadUsers();
			const me = users.find(u => u.email === normalizeEmail(session.email));
			if (!me || me.deleted) {
				clearSession();
				updateUserUI();
				return;
			}
			userArea.innerHTML = `
				<span style="margin-right:8px;">Hola, ${me.nombre}</span>
				<a class="boton" href="perfil.html">Perfil</a>
				<button id="logout-btn" class="boton">Cerrar sesión</button>
			`;
			const btn = document.getElementById('logout-btn');
			if (btn) btn.addEventListener('click', function(){ clearSession(); updateUserUI(); });
		} else {
			userArea.innerHTML = `<a class="boton" href="login.html">Entrar</a> <a class="boton" href="registro.html">Registro</a>`;
		}
	}

	// registro desde registro.html
	const formRegistro = document.getElementById('form-registro');
	if (formRegistro) {
		formRegistro.addEventListener('submit', function(e){
			e.preventDefault();
			const nombre = document.getElementById('reg-nombre').value.trim();
			const email = normalizeEmail(document.getElementById('reg-email').value);
			const password = document.getElementById('reg-password').value;
			const confirmarPassword = document.getElementById('reg-confirm-password').value;
			if (!nombre || !email || !password || !confirmarPassword || password.length < 4) {
				alert('Completa los campos. La contraseña debe tener al menos 4 caracteres.');
				return;
			}
			if (password !== confirmarPassword) {
				alert('Las contraseñas no coinciden. Verifica que sean iguales.');
				return;
			}
			const users = loadUsers();
			if (users.some(function (u) { return u.email === email; })) {
				alert('Ya existe una cuenta con ese correo.');
				return;
			}
			users.push({
				nombre: nombre,
				email: email,
				password: password,
				role: 'user',
				deleted: false,
				deletedAt: null
			});
			saveUsers(users);
			setSession(email);
			updateUserUI();
			alert('Registro exitoso. Sesión iniciada.');
			window.location.href = 'index.html';
		});
	}

	// login desde login.html
	const formLogin = document.getElementById('form-login');
	if (formLogin) {
		formLogin.addEventListener('submit', function(e){
			e.preventDefault();
			const email = normalizeEmail(document.getElementById('login-email').value);
			const password = document.getElementById('login-password').value;
			const users = loadUsers();
			const me = users.find(function (u) { return u.email === email && u.password === password; });
			if (!me || me.deleted) {
				alert('Credenciales inválidas');
				return;
			}
			setSession(email);
			updateUserUI();
			alert('Sesión iniciada');
			window.location.href = 'index.html';
		});
	}

	// perfil y historial de pedidos
	const perfilApp = document.getElementById('perfil-app');
	if (perfilApp) {
		function renderAdminUsersPanel() {
			const users = loadUsers();
			const activeUsers = users.filter(function (user) { return !user.deleted; });
			const deletedUsers = users.filter(function (user) { return user.deleted; });

			return `
				<div class="perfil-card admin-card">
					<h3>Administración de cuentas</h3>
					<p class="admin-subtitle">Total de cuentas: ${users.length}</p>
					<div class="admin-table-wrap">
						<table class="admin-table">
							<thead>
								<tr>
									<th>Nombre</th>
									<th>Correo</th>
									<th>Rol</th>
									<th>Estado</th>
									<th>Acción</th>
								</tr>
							</thead>
							<tbody>
								${activeUsers.length ? activeUsers.map(function (user) {
									return `
										<tr>
											<td>${user.nombre}</td>
											<td>${user.email}</td>
											<td><span class="badge ${user.role === 'admin' ? 'badge-admin' : 'badge-user'}">${user.role === 'admin' ? 'Admin' : 'Usuario'}</span></td>
											<td><span class="badge badge-active">Activa</span></td>
											<td>${user.role === 'admin' ? '<span class="text-muted">Protegida</span>' : '<button class="boton boton-peligro" data-user-action="delete" data-user-email="' + user.email + '">Eliminar</button>'}</td>
										</tr>
									`;
								}).join('') : '<tr><td colspan="5">No hay cuentas activas.</td></tr>'}
							</tbody>
						</table>
					</div>
					${deletedUsers.length ? `
						<div class="admin-deleted-section">
							<h4>Cuentas eliminadas</h4>
							<div class="admin-deleted-list">
								${deletedUsers.map(function (user) {
									return `
										<div class="admin-deleted-item">
											<span>${user.nombre} · ${user.email}</span>
											<button class="boton boton-exito" data-user-action="restore" data-user-email="${user.email}">Recuperar</button>
										</div>
									`;
								}).join('')}
							</div>
						</div>
					` : ''}
				</div>
			`;
		}

		function renderPerfil() {
			const session = getSession();
			const user = getCurrentUser();
			if (!session || !user) {
				perfilApp.innerHTML = `
					<div class="perfil-vacio">
						<h2>Mi perfil</h2>
						<p>Inicia sesión para ver tu historial de compras.</p>
						<a class="boton" href="login.html">Iniciar sesión</a>
					</div>
				`;
				return;
			}

			const isAdmin = user.role === 'admin' || user.email === ADMIN_EMAIL;
			const orders = getUserOrders(session.email).slice().reverse();
			const userName = user.nombre || user.email.split('@')[0];
			const adminPanel = isAdmin ? renderAdminUsersPanel() : '';

			perfilApp.innerHTML = `
				<div class="perfil-header">
					<h2>${isAdmin ? 'Panel de administración' : 'Mi perfil'}</h2>
					<p>Bienvenido/a, ${userName}</p>
				</div>
				<div class="perfil-grid">
					<div class="perfil-card">
						<h3>Datos personales</h3>
						<p><strong>Nombre:</strong> ${user.nombre || userName}</p>
						<p><strong>Correo:</strong> ${user.email}</p>
						<button id="logout-perfil" class="boton">Cerrar sesión</button>
						${!isAdmin ? '<button id="delete-perfil" class="boton boton-peligro" style="margin-left:8px;">Eliminar perfil</button>' : ''}
					</div>
					${!isAdmin ? `
						<div class="perfil-card">
							<h3>Editar perfil</h3>
							<form id="form-editar-perfil">
								<div class="grupo-formulario">
									<label for="perfil-nombre">Nombre</label>
									<input id="perfil-nombre" type="text" value="${(user.nombre || userName).replace(/"/g, '&quot;')}" required>
								</div>
								<div class="grupo-formulario">
									<label for="perfil-email">Correo electrónico</label>
									<input id="perfil-email" type="email" value="${(user.email || '').replace(/"/g, '&quot;')}" required>
								</div>
								<div class="grupo-formulario">
									<label for="perfil-password">Nueva contraseña (opcional)</label>
									<input id="perfil-password" type="password" placeholder="Deja en blanco para no cambiarla">
								</div>
								<div class="grupo-formulario">
									<label for="perfil-confirm-password">Confirmar nueva contraseña</label>
									<input id="perfil-confirm-password" type="password" placeholder="Repite la nueva contraseña">
								</div>
								<button type="submit" class="boton">Guardar cambios</button>
							</form>
						</div>
					` : ''}
					${!isAdmin ? `
						<div class="perfil-card">
							<h3>Historial de pedidos</h3>
							${orders.length ? orders.map(function (order) {
								return `
									<article class="pedido-card">
										<div class="pedido-header">
											<span class="pedido-id">${order.id}</span>
											<span class="pedido-estado">${order.estado}</span>
										</div>
										<div class="pedido-meta">
											<span>${new Date(order.fecha).toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' })}</span>
											<span>Total: RD$ ${Number(order.total).toLocaleString()}</span>
										</div>
										<div class="pedido-items">
											${order.items.map(function(item){
												return `<div class="pedido-item">${item.cantidad}× ${item.nombre} — RD$ ${(item.precio * item.cantidad).toLocaleString()}</div>`;
											}).join('')}
										</div>
									</article>
								`;
							}).join('') : '<p class="pedido-vacio">Aún no tienes pedidos registrados.</p>'}
						</div>
					` : ''}
				</div>
				${adminPanel}
			`;

			const logoutPerfilBtn = document.getElementById('logout-perfil');
			if (logoutPerfilBtn) {
				logoutPerfilBtn.addEventListener('click', function () {
					clearSession();
					updateUserUI();
					renderPerfil();
				});
			}

			const deletePerfilBtn = document.getElementById('delete-perfil');
			if (deletePerfilBtn) {
				deletePerfilBtn.addEventListener('click', function () {
					const sessionActual = getSession();
					if (!sessionActual || !sessionActual.email) return;
					const confirmar = window.confirm('¿Seguro que quieres eliminar tu perfil? Esta acción lo marcará como inactivo y podrás recuperarlo más adelante.');
					if (!confirmar) return;

					const users = loadUsers();
					const indexActual = users.findIndex(function (u) { return u.email === normalizeEmail(sessionActual.email); });
					if (indexActual !== -1) {
						users[indexActual].deleted = true;
						users[indexActual].deletedAt = new Date().toISOString();
						saveUsers(users);
					}
					clearSession();
					updateUserUI();
					alert('Perfil eliminado correctamente.');
					window.location.href = 'index.html';
				});
			}

			const formEditarPerfil = document.getElementById('form-editar-perfil');
			if (formEditarPerfil) {
				formEditarPerfil.addEventListener('submit', function (event) {
					event.preventDefault();
					const nuevoNombre = document.getElementById('perfil-nombre').value.trim();
					const nuevoEmail = normalizeEmail(document.getElementById('perfil-email').value);
					const nuevaPassword = document.getElementById('perfil-password').value;
					const confirmarPassword = document.getElementById('perfil-confirm-password').value;

					if (!nuevoNombre || !nuevoEmail) {
						alert('Nombre y correo son obligatorios.');
						return;
					}

					if (nuevaPassword || confirmarPassword) {
						if (nuevaPassword.length < 4) {
							alert('La nueva contraseña debe tener al menos 4 caracteres.');
							return;
						}
						if (nuevaPassword !== confirmarPassword) {
							alert('La nueva contraseña y su confirmación no coinciden.');
							return;
						}
					}

					const users = loadUsers();
					const indexActual = users.findIndex(function (u) { return u.email === session.email; });
					if (indexActual === -1) {
						alert('No se encontró tu usuario activo.');
						return;
					}

					const emailDuplicado = users.find(function (u, idx) {
						return idx !== indexActual && u.email.toLowerCase() === nuevoEmail.toLowerCase();
					});
					if (emailDuplicado) {
						alert('Ya existe otra cuenta con ese correo.');
						return;
					}

					users[indexActual].nombre = nuevoNombre;
					users[indexActual].email = nuevoEmail;
					if (nuevaPassword) {
						users[indexActual].password = nuevaPassword;
					}

					saveUsers(users);
					setSession(nuevoEmail);
					updateUserUI();
					renderPerfil();
					alert('Perfil actualizado correctamente.');
				});
			}

			const adminButtons = document.querySelectorAll('[data-user-action]');
			adminButtons.forEach(function (button) {
				button.addEventListener('click', function () {
					const userEmail = normalizeEmail(button.dataset.userEmail);
					const action = button.dataset.userAction;
					if (!userEmail) return;

					const users = loadUsers();
					const userToManage = users.find(function (u) { return u.email === userEmail; });
					if (!userToManage) return;

					if (userToManage.role === 'admin') {
						alert('La cuenta del administrador no puede eliminarse ni recuperarse desde este panel.');
						return;
					}

					if (action === 'delete') {
						const confirmar = window.confirm('¿Seguro que quieres eliminar esta cuenta? Se marcará como inactiva y podrá recuperarse más adelante.');
						if (!confirmar) return;
						userToManage.deleted = true;
						userToManage.deletedAt = new Date().toISOString();
						saveUsers(users);
						renderPerfil();
						alert('Cuenta eliminada correctamente.');
					}

					if (action === 'restore') {
						userToManage.deleted = false;
						userToManage.deletedAt = null;
						saveUsers(users);
						renderPerfil();
						alert('Cuenta recuperada correctamente.');
					}
				});
			});
		}

		renderPerfil();
	}

	// inicializar UI de usuario al cargar
	updateUserUI();

	// ==========================================
	// CARRITO (página carrito.html)
	// ==========================================

	const carritoAppPage = document.getElementById('carrito-app');
	if (carritoAppPage) {
		const resumenItemsPage = document.getElementById('resumen-items');
		const subtotalElPage = document.getElementById('subtotal');
		const totalElPage = document.getElementById('total');
		const vaciarBtnPage = document.getElementById('vaciar-carrito-page');

		function aggregateCartPage() {
			const map = {};
			carrito.forEach(function (item) {
				if (map[item.nombre]) {
					map[item.nombre].cantidad++;
				} else {
					map[item.nombre] = {
						nombre: item.nombre,
						precio: item.precio,
						cantidad: 1
					};
				}
			});
			return Object.values(map);
		}

		function renderCartPage() {
			const items = aggregateCartPage();
			resumenItemsPage.innerHTML = '';
			let subtotal = 0;

			items.forEach(function (prod) {
				const row = document.createElement('div');
				row.className = 'item-row';

				const nombreDiv = document.createElement('div');
				nombreDiv.textContent = prod.nombre;

				const controles = document.createElement('div');
				controles.className = 'controles';

				const btnMinus = document.createElement('button');
				btnMinus.className = 'boton btn-cantidad';
				btnMinus.textContent = '−';
				btnMinus.dataset.nombre = prod.nombre;
				btnMinus.dataset.delta = -1;

				const spanCant = document.createElement('span');
				spanCant.textContent = prod.cantidad;

				const btnPlus = document.createElement('button');
				btnPlus.className = 'boton btn-cantidad';
				btnPlus.textContent = '+';
				btnPlus.dataset.nombre = prod.nombre;
				btnPlus.dataset.delta = 1;

				const btnEliminar = document.createElement('button');
				btnEliminar.className = 'boton btn-eliminar';
				btnEliminar.textContent = '🗑️';
				btnEliminar.dataset.nombre = prod.nombre;

				const precioDiv = document.createElement('div');
				precioDiv.style.minWidth = '90px';
				precioDiv.style.textAlign = 'right';
				precioDiv.textContent = `RD$ ${(prod.precio * prod.cantidad).toLocaleString()}`;

				controles.appendChild(btnMinus);
				controles.appendChild(spanCant);
				controles.appendChild(btnPlus);
				controles.appendChild(btnEliminar);
				controles.appendChild(precioDiv);

				row.appendChild(nombreDiv);
				row.appendChild(controles);

				resumenItemsPage.appendChild(row);

				subtotal += prod.precio * prod.cantidad;
			});

			subtotalElPage.textContent = `RD$ ${subtotal.toLocaleString()}`;
			totalElPage.textContent = `RD$ ${subtotal.toLocaleString()}`;

			// listeners
			resumenItemsPage.querySelectorAll('.btn-cantidad').forEach(function (btn) {
				btn.addEventListener('click', function () {
					const nombre = this.dataset.nombre;
					const delta = Number(this.dataset.delta);
					changeQuantityPage(nombre, delta);
					renderCartPage();
				});
			});

			resumenItemsPage.querySelectorAll('.btn-eliminar').forEach(function (btn) {
				btn.addEventListener('click', function () {
					const nombre = this.dataset.nombre;
					removeItemPage(nombre);
					renderCartPage();
				});
			});
		}

		function changeQuantityPage(nombre, delta) {
			if (delta > 0) {
				const found = carrito.find(function (i) { return i.nombre === nombre; });
				if (found) carrito.push({ nombre: found.nombre, precio: found.precio });
			} else {
				const idx = carrito.findIndex(function (i) { return i.nombre === nombre; });
				if (idx > -1) carrito.splice(idx, 1);
			}
			guardarCarrito();
			actualizarContador();
		}

		function removeItemPage(nombre) {
			carrito = carrito.filter(function (i) { return i.nombre !== nombre; });
			guardarCarrito();
			actualizarContador();
		}

		if (vaciarBtnPage) {
			vaciarBtnPage.addEventListener('click', function () {
				vaciarCarrito();
				renderCartPage();
			});
		}

		// inicial render
		renderCartPage();
	}
});
