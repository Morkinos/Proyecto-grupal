import React from 'react';

class Menu extends React.Component {
    render() {
        const { activeComponent, setActiveComponent } = this.props;

        return (
            <div className="container">
                <nav className='navbar navbar-expand-lg navbar-dark bg-dark fixed-top'>
                    <ul className='navbar-nav mr-auto'>
                        <li className='nav-item'>
                            <button className='nav-link btn btn-link text-white'
                                onClick={() => setActiveComponent('Home')}>
                                Inicio
                            </button>
                        </li>
                        <li className='nav-item'>
                            <button className='nav-link btn btn-link text-white'
                                onClick={() => setActiveComponent('Album')}>
                                Álbum
                            </button>
                        </li>
                        <li className='nav-item'>
                            <button className='nav-link btn btn-link text-white'
                                onClick={() => setActiveComponent('User')}>
                                Usuario
                            </button>
                        </li>
                        <li className='nav-item'>
                            <button className='nav-link btn btn-link text-white'
                                onClick={() => setActiveComponent('Artist')}>
                                Artista
                            </button>
                        </li>
                        <li className='nav-item'>
                            <button className='nav-link btn btn-link text-white'
                                onClick={() => setActiveComponent('Purchase')}>
                                Compra
                            </button>
                        </li>
                    </ul>
                </nav>
            </div>
        );
    }
}

export default Menu;
