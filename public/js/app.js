function toggleSidebar() {
    const sidebar = document.getElementById('mobile-sidebar');
    const overlay = document.getElementById('mobile-overlay');
    
    if (sidebar.classList.contains('-translate-x-full')) {
        // Open
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
    } else {
        // Close
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
    }
}

// Auto-hide notifications after 5 seconds
document.addEventListener('DOMContentLoaded', () => {
    const notification = document.getElementById('notification');
    if (notification) {
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transition = 'opacity 0.5s ease';
            setTimeout(() => {
                notification.style.display = 'none';
            }, 500);
        }, 5000);
    }
});

// Dark mode toggle logic
const themeToggleBtn = document.getElementById('themeToggle');

if(themeToggleBtn) {
    themeToggleBtn.addEventListener('click', function() {
        if (document.documentElement.classList.contains('dark')) {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        } else {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        }
    });
}

// ==========================================
// Interactive Physics Fidget Canvas (Fluid/Particles)
// ==========================================
const canvas = document.getElementById('fidgetCanvas');
if(canvas) {
    const ctx = canvas.getContext('2d');
    
    let width, height;
    function resize() {
        width = canvas.width = canvas.parentElement.clientWidth;
        height = canvas.height = canvas.parentElement.clientHeight;
    }
    window.addEventListener('resize', resize);
    resize();

    let mouse = { x: width/2, y: height/2, vx: 0, vy: 0, isDown: false };
    
    canvas.addEventListener('mousemove', (e) => {
        const rect = canvas.getBoundingClientRect();
        const nx = e.clientX - rect.left;
        const ny = e.clientY - rect.top;
        mouse.vx = nx - mouse.x;
        mouse.vy = ny - mouse.y;
        mouse.x = nx;
        mouse.y = ny;
    });
    
    canvas.addEventListener('mousedown', () => mouse.isDown = true);
    canvas.addEventListener('mouseup', () => mouse.isDown = false);
    canvas.addEventListener('mouseleave', () => mouse.isDown = false);

    class Boid {
        constructor() {
            this.x = Math.random() * width;
            this.y = Math.random() * height;
            this.vx = (Math.random() - 0.5) * 2;
            this.vy = (Math.random() - 0.5) * 2;
            this.radius = Math.random() * 4 + 2;
            this.color = Math.random() > 0.5 ? '#4f46e5' : (Math.random() > 0.5 ? '#ec4899' : '#06b6d4');
        }

        update() {
            // Apply mouse interaction
            const dx = mouse.x - this.x;
            const dy = mouse.y - this.y;
            const dist = Math.sqrt(dx*dx + dy*dy);
            
            if (dist < 150) {
                const force = (150 - dist) / 150;
                if (mouse.isDown) {
                    // Attract strongly
                    this.vx += (dx / dist) * force * 1.5;
                    this.vy += (dy / dist) * force * 1.5;
                } else {
                    // Repel gently
                    this.vx -= (dx / dist) * force * 0.5;
                    this.vy -= (dy / dist) * force * 0.5;
                }
                
                // Add mouse velocity if moving fast
                if(Math.abs(mouse.vx) > 1 || Math.abs(mouse.vy) > 1) {
                    this.vx += mouse.vx * force * 0.05;
                    this.vy += mouse.vy * force * 0.05;
                }
            }

            // Move
            this.x += this.vx;
            this.y += this.vy;

            // Friction
            this.vx *= 0.98;
            this.vy *= 0.98;
            
            // Minimum speed
            if(Math.abs(this.vx) < 0.2) this.vx += (Math.random()-0.5)*0.1;
            if(Math.abs(this.vy) < 0.2) this.vy += (Math.random()-0.5)*0.1;

            // Bounce off walls
            if (this.x < 0) { this.x = 0; this.vx *= -1; }
            if (this.x > width) { this.x = width; this.vx *= -1; }
            if (this.y < 0) { this.y = 0; this.vy *= -1; }
            if (this.y > height) { this.y = height; this.vy *= -1; }
        }

        draw() {
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
            ctx.fillStyle = this.color;
            ctx.globalAlpha = document.documentElement.classList.contains('dark') ? 0.6 : 0.3;
            ctx.fill();
        }
    }

    const boids = Array.from({length: 60}, () => new Boid());

    function animate() {
        ctx.clearRect(0, 0, width, height);
        
        // Connect nearby boids
        ctx.lineWidth = 1;
        for(let i=0; i<boids.length; i++) {
            for(let j=i+1; j<boids.length; j++) {
                const dx = boids[i].x - boids[j].x;
                const dy = boids[i].y - boids[j].y;
                const dist = Math.sqrt(dx*dx + dy*dy);
                
                if(dist < 100) {
                    ctx.beginPath();
                    ctx.moveTo(boids[i].x, boids[i].y);
                    ctx.lineTo(boids[j].x, boids[j].y);
                    const alpha = (100 - dist) / 100 * (document.documentElement.classList.contains('dark') ? 0.3 : 0.1);
                    ctx.strokeStyle = `rgba(79, 70, 229, ${alpha})`;
                    ctx.stroke();
                }
            }
        }

        boids.forEach(b => {
            b.update();
            b.draw();
        });

        // Reset mouse velocity slightly each frame to simulate stopping
        mouse.vx *= 0.8;
        mouse.vy *= 0.8;

        requestAnimationFrame(animate);
    }
    
    animate();
}
