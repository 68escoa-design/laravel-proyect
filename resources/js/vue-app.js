// Vue.js Application Setup
import { createApp } from 'vue';

const app = createApp({
    name: 'GymApp',
    data() {
        return {
            // Authentication
            token: localStorage.getItem('token') || null,
            user: JSON.parse(localStorage.getItem('user')) || null,
            
            // Loading states
            loading: false,
            appLoaded: false,
            error: null,
            success: null,
            
            // Login form
            loginForm: {
                email: '',
                password: ''
            },
            
            // Current route
            currentRoute: window.location.hash.slice(1) || '/login',
            
            // API base URL
            apiUrl: 'http://localhost:8000/api',
            
            // Data for different modules
            clients: [],
            plans: [],
            payments: [],
            exercises: [],
            routines: [],
            schedules: [],
            attendance: [],
            
            // Forms
            clientForm: {
                name: '',
                email: '',
                password: '',
                phone: '',
                address: '',
                birth_date: ''
            },
            planForm: {
                name: '',
                description: '',
                price: '',
                weekly_sessions: '',
                monthly_sessions: ''
            },
            paymentForm: {
                client_id: '',
                amount: '',
                payment_method: 'cash',
                reference: '',
                notes: ''
            },
            exerciseForm: {
                name: '',
                description: '',
                muscle_group: '',
                equipment: '',
                instructions: ''
            },
            routineForm: {
                client_id: '',
                name: '',
                description: '',
                exercises: []
            }
        }
    },
    
    computed: {
        isAuthenticated() {
            return !!this.token;
        },
        
        isAdmin() {
            return this.user && this.user.role === 'admin';
        },
        
        isTrainer() {
            return this.user && this.user.role === 'trainer';
        },
        
        isClient() {
            return this.user && this.user.role === 'client';
        }
    },
    
    mounted() {
        console.log('Vue app mounted - INICIO');
        
        // Check if element exists
        const vueAppElement = document.getElementById('vue-app');
        console.log('Elemento #vue-app existe:', !!vueAppElement);
        console.log('Elemento #vue-app:', vueAppElement);
        console.log('innerHTML del elemento:', vueAppElement.innerHTML.substring(0, 200));
        
        // Mark app as loaded FIRST
        this.appLoaded = true;
        
        console.log('appLoaded DESPUÉS de marcar:', this.appLoaded);
        console.log('isAuthenticated:', this.isAuthenticated);
        console.log('currentRoute:', this.currentRoute);
        console.log('user:', this.user);
        
        // Force Vue to re-render
        this.$forceUpdate();
        
        // Check after update
        setTimeout(() => {
            console.log('innerHTML después de forceUpdate:', document.getElementById('vue-app').innerHTML.substring(0, 200));
        }, 100);
        
        // Check if user is authenticated
        if (this.isAuthenticated) {
            this.currentRoute = '/dashboard';
            if (window.location.hash === '' || window.location.hash === '#/login') {
                window.location.hash = '/dashboard';
            }
        } else {
            this.currentRoute = '/login';
            window.location.hash = '/login';
        }
        
        // Handle hash changes
        window.addEventListener('hashchange', () => {
            this.currentRoute = window.location.hash.slice(1) || '/login';
        });
        
        // Load initial data based on route
        this.loadRouteData();
    },
    
    watch: {
        currentRoute(newRoute) {
            this.loadRouteData();
        }
    },
    
    methods: {
        async loadRouteData() {
            if (!this.isAuthenticated) return;
            
            try {
                switch(this.currentRoute) {
                    case '/dashboard':
                        // No cargar datos automáticamente en el dashboard
                        break;
                    case '/clients':
                        await this.loadClients();
                        break;
                    case '/plans':
                        await this.loadPlans();
                        break;
                    case '/payments':
                        await this.loadPayments();
                        break;
                    case '/exercises':
                        await this.loadExercises();
                        break;
                    case '/routines':
                        await this.loadRoutines();
                        break;
                    case '/my-routines':
                        await this.loadMyRoutines();
                        break;
                    case '/my-attendance':
                        await this.loadMyAttendance();
                        break;
                }
            } catch (error) {
                console.error('Error al cargar datos:', error);
                // Solo mostrar error si no es un error de permisos
                if (!error.message.includes('No tienes permiso')) {
                    this.error = 'Error al cargar datos: ' + error.message;
                }
            }
        },
        
        async login() {
            try {
                this.loading = true;
                const response = await fetch(`${this.apiUrl}/login`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.loginForm)
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    this.token = data.token;
                    this.user = data.user;
                    
                    localStorage.setItem('token', data.token);
                    localStorage.setItem('user', JSON.stringify(data.user));
                    
                    this.loginForm.email = '';
                    this.loginForm.password = '';
                    
                    this.success = 'Login exitoso';
                    this.currentRoute = '/dashboard';
                    window.location.hash = '/dashboard';
                } else {
                    this.error = 'Error de login: ' + (data.message || 'Credenciales incorrectas');
                }
            } catch (error) {
                console.error('Error de login:', error);
                this.error = 'Error de conexión con el servidor';
            } finally {
                this.loading = false;
            }
        },
        
        logout() {
            this.token = null;
            this.user = null;
            
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            
            this.currentRoute = '/login';
            window.location.hash = '/login';
        },
        
        async apiCall(endpoint, method = 'GET', body = null) {
            try {
                const headers = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                };
                
                if (this.token) {
                    headers['Authorization'] = `Bearer ${this.token}`;
                }
                
                const options = {
                    method,
                    headers
                };
                
                if (body) {
                    options.body = JSON.stringify(body);
                }
                
                const response = await fetch(`${this.apiUrl}${endpoint}`, options);
                const data = await response.json();
                
                if (!response.ok) {
                    // Silenciar errores de permisos en consola pero aún lanzar el error
                    if (response.status === 403) {
                        console.warn('Permiso denegado para:', endpoint);
                    }
                    throw new Error(data.message || 'Error en la petición');
                }
                
                return data;
            } catch (error) {
                console.error('API Error:', error);
                throw error;
            }
        },
        
        // Load methods
        async loadClients() {
            this.clients = await this.apiCall('/clients');
        },
        
        async loadPlans() {
            this.plans = await this.apiCall('/plans');
        },
        
        async loadPayments() {
            this.payments = await this.apiCall('/payments');
        },
        
        async loadExercises() {
            this.exercises = await this.apiCall('/exercises');
        },
        
        async loadRoutines() {
            this.routines = await this.apiCall('/routines');
        },
        
        async loadMyRoutines() {
            this.routines = await this.apiCall('/my-routines');
        },
        
        async loadMyAttendance() {
            this.attendance = await this.apiCall('/my-attendance');
        },
        
        // Create methods
        async createClient() {
            try {
                this.loading = true;
                await this.apiCall('/clients', 'POST', this.clientForm);
                this.success = 'Cliente creado exitosamente';
                this.clientForm = { name: '', email: '', password: '', phone: '', address: '', birth_date: '' };
                await this.loadClients();
            } catch (error) {
                this.error = 'Error al crear cliente: ' + error.message;
            } finally {
                this.loading = false;
            }
        },
        
        async createPlan() {
            try {
                this.loading = true;
                await this.apiCall('/plans', 'POST', this.planForm);
                this.success = 'Plan creado exitosamente';
                this.planForm = { name: '', description: '', price: '', weekly_sessions: '', monthly_sessions: '' };
                await this.loadPlans();
            } catch (error) {
                this.error = 'Error al crear plan: ' + error.message;
            } finally {
                this.loading = false;
            }
        },
        
        async createPayment() {
            try {
                this.loading = true;
                await this.apiCall('/payments', 'POST', this.paymentForm);
                this.success = 'Pago registrado exitosamente';
                this.paymentForm = { client_id: '', amount: '', payment_method: 'cash', reference: '', notes: '' };
                await this.loadPayments();
            } catch (error) {
                this.error = 'Error al registrar pago: ' + error.message;
            } finally {
                this.loading = false;
            }
        },
        
        async createExercise() {
            try {
                this.loading = true;
                await this.apiCall('/exercises', 'POST', this.exerciseForm);
                this.success = 'Ejercicio creado exitosamente';
                this.exerciseForm = { name: '', description: '', muscle_group: '', equipment: '', instructions: '' };
                await this.loadExercises();
            } catch (error) {
                this.error = 'Error al crear ejercicio: ' + error.message;
            } finally {
                this.loading = false;
            }
        },
        
        async createRoutine() {
            try {
                this.loading = true;
                await this.apiCall('/routines', 'POST', this.routineForm);
                this.success = 'Rutina creada exitosamente';
                this.routineForm = { client_id: '', name: '', description: '', exercises: [] };
                await this.loadRoutines();
            } catch (error) {
                this.error = 'Error al crear rutina: ' + error.message;
            } finally {
                this.loading = false;
            }
        },
        
        // Delete methods
        async deleteClient(id) {
            if (confirm('¿Estás seguro de eliminar este cliente?')) {
                try {
                    await this.apiCall(`/clients/${id}`, 'DELETE');
                    this.success = 'Cliente eliminado exitosamente';
                    await this.loadClients();
                } catch (error) {
                    this.error = 'Error al eliminar cliente: ' + error.message;
                }
            }
        },
        
        async deletePlan(id) {
            if (confirm('¿Estás seguro de eliminar este plan?')) {
                try {
                    await this.apiCall(`/plans/${id}`, 'DELETE');
                    this.success = 'Plan eliminado exitosamente';
                    await this.loadPlans();
                } catch (error) {
                    this.error = 'Error al eliminar plan: ' + error.message;
                }
            }
        },
        
        navigate(path) {
            this.currentRoute = path;
            window.location.hash = path;
        }
    }
});

// Mount the app
app.mount('#vue-app');