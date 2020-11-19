import { Request, Response, Router } from "express";
import auth from "./auth";
import users from "./users";
import tasks from "./tasks";

const routes = Router();

routes.use("/auth", auth);
routes.use("/users", users);
routes.use("/tasks", tasks);
routes.get("/testmode", (req: Request, res: Response) => {
    if (req.app.locals.config.TEST_MODE) {
        res.send(`
        <!DOCTYPE html>
        <html>
            <body>
                <button onclick='document.cookie = "forceDay="+parseInt(prompt("Aktueller Tag"));'>Aktuellen Tag setzen</button>
                <button onclick='document.cookie = "forceDay= ; expires = Thu, 01 Jan 1970 00:00:00 GMT";'>Zurücksetzen</button>
            </body>
        </html>
        `);
    } else {
        res.send("Test Mode is not enabled!");
    }
});

export default routes;
