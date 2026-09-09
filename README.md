# Roséa Boutique

Sitio estático de ejemplo para una boutique con estilo romántico "coquette".

Contenido principal:

- `index.html` — Página de inicio
- `coleccion.html` — Página de colección con buscador, filtros y carrito
- `contacto.html` — Formulario de contacto
- `carrito.html` — Vista independiente del carrito
- `checkout.html` — Página de finalización de compra
- `css/estilos.css` — Estilos principales
- `js/script.js` — Lógica del carrito, checkout, filtros, autenticación (localStorage)
- `img/` — Imágenes de producto

Estado: Prototype funcional (local). JavaScript usa `localStorage` para persistencia de carrito y autenticación simulada.

Instrucciones rápidas para subir a GitHub:

```bash
cd "C:/Users/Frank/Desktop/Rosea-Boutique"
# Inicializar repo (si aún no existe)
git init
# Añadir archivos
git add .
# Primer commit
git commit -m "Init: Roséa Boutique site"
# Ver estado
git status
# (Opcional) Crear un repositorio en GitHub y añadir remote
git remote add origin https://github.com/<tu-usuario>/<tu-repo>.git
git branch -M main
git push -u origin main
```

Notas:
- Revisa `README.md` y edítalo con tu descripción y licencia antes de subir.
- `localStorage` se usa para demo; no es seguro para producción.
