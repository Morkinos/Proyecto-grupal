import React from 'react';

class Menu extends React.Component {
    render() { 
        const { activeComponent, setActiveComponent } = this.props;

        return ( 
            <div className="container">               
                <nav className='navbar navbar-expand-lg navbar-light bg-light fixed-top'>
                    <ul className='navbar-nav mr-auto'>
                        <li className='nav-item'>
                            <button className='nav-link btn btn-link'
                            onClick={() => setActiveComponent('Album')}>
                                Album
                            </button>
                        </li>
                        <li className='nav-item'>
                            <button className='nav-link btn btn-link'
                            onClick={() => setActiveComponent('User')}>
                                User
                            </button>
                        </li>
                        <li className='nav-item'>
                            <button className='nav-link btn btn-link'
                            onClick={() => setActiveComponent('Artist')}>
                                Artist
                            </button>
                        </li>
                        <li className='nav-item'>
                            <button className='nav-link btn btn-link'
                            onClick={() => setActiveComponent('Purchase')}>
                                Purchase
                            </button>
                        </li>
                    </ul>
                </nav>
            </div>
        );
    }
}
 
export default Menu;
