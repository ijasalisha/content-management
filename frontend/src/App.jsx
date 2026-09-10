import { useEffect, useState } from 'react'
import heroImg from './assets/hero.png'
import reactLogo from './assets/react.svg'
import viteLogo from './assets/vite.svg'
import './App.css'

const API_URL = "http://127.0.0.1:8000/api";
function App() {
  const [menus, setMenus] = useState([]);
  const [pages, setPages] = useState([]);
  const [selectedPage, setSelectedPage] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    Promise.all([
      fetch(`${API_URL}/public/menus`).then((res) => res.json()),
      fetch(`${API_URL}/public/pages`).then((res) => res.json()),
    ])
      .then(([menuData, pageData]) => {
        setMenus(menuData.data || []);
        setPages(pageData.data || []);
      })
      .catch((error) => {
        console.error("API Error:", error);
      })
      .finally(() => {
        setLoading(false);
      });
  }, []);

  const openPage = async (id) => {
    const response = await fetch(`${API_URL}/public/pages/${id}`);
    const data = await response.json();
    setSelectedPage(data.data);
  };

  if (loading) {
    return <div className="container">Loading...</div>;
  }

  return (
    <>
      <header>
        <div className="container">
          <h1>Content Management System</h1>

          <nav>
            {menus.map((menu) => (
              <div key={menu.id}>
                <button onClick={() => openPage(menu.pages?.[0]?.id)}>
                  {menu.title}
                </button>

                {menu.children?.map((child) => (
                  <button
                    key={child.id}
                    onClick={() => openPage(child.pages?.[0]?.id)}
                  >
                    {child.title}
                  </button>
                ))}
              </div>
            ))}
          </nav>
        </div>
      </header>

      <main className="container">
        {selectedPage ? (
          <article>
            <button onClick={() => setSelectedPage(null)}>
              ← Back
            </button>

            <h2>{selectedPage.title}</h2>

            {selectedPage.cover_image && (
              <img
                src={selectedPage.cover_image}
                alt={selectedPage.title}
              />
            )}

            <div
              dangerouslySetInnerHTML={{ __html: selectedPage.body }}
            />
          </article>
        ) : (
          <>
            <h2>Published Pages</h2>

            <div className="page-list">
              {pages.map((page) => (
                <button
                  className="page-card"
                  key={page.id}
                  onClick={() => openPage(page.id)}
                >
                  <h3>{page.title}</h3>
                </button>
              ))}
            </div>
          </>
        )}
      </main>
    </>
  )
}

export default App;
