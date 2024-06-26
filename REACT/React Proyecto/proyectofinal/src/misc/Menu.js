import React from 'react';

class Menu extends React.Component {
   
    render() { 

        //creamos las variables para la carga 
        const { activeComponent, SetActiveComponent } = this.props;

        return ( 
            <div className="container">               
                <nav className='navbar navbar-expand-lg navbar-light bg-light fixed-top'>
                        <ul className='navbar-nav mr-auto'>
                            <li className='nav-item'>
                                <button className='nav-link btn btn-link'
                                onClick={()=> SetActiveComponent('Album')}>
                                    Album
                                </button>
                            </li>
                            <li className='nav-item'>
                                <button className='nav-link btn btn-link'
                                onClick={()=> SetActiveComponent('user')}>
                                    User
                                </button>
                            </li>
                            <li className='nav-item'>
                                <button className='nav-link btn btn-link'>
                                    Profesores
                                </button>
                            </li>
                            <li className='nav-item'>
                                <button className='nav-link btn btn-link'>
                                    Estudiantes
                                </button>
                            </li>
                            <li className='nav-item'>
                                <button className='nav-link btn btn-link'>
                                    Usuarios
                                </button>
                            </li>  
                                                                                      
                        </ul>
                    </nav>
            </div>
            
         );
    }
}
 
export default Menu;
